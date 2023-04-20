<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarEventBean
 * @author Hugues
 * @since 1.22.11.22
 * @version 1.22.11.26
 */
class WpPageAdminCalendarEventBean extends WpPageAdminCalendarBean
{
    public function __construct()
    {
        parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_EVENT;
        $this->titreSubOnglet = self::LABEL_EVENTS;
        /////////////////////////////////////////
        // Définition des services
        $this->objCopsEventServices = new CopsEventServices();

        /////////////////////////////////////////
        // On initialise l'éventuelle pagination & on ajoute à l'url de Refresh
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        $this->action = $this->initVar(self::CST_ACTION);
        $id = $this->initVar(self::FIELD_ID);
        $this->objCopsEvent = $this->objCopsEventServices->getCopsEvent($id);

        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////

        if (isset($_POST) && isset($this->urlParams[self::CST_WRITE_ACTION])) {
            $this->dealWithWriteAction();
        }

        /*

    if (isset($_POST) && !empty($_POST)) {
      $CopsEvent = new CopsEvent();

      if (trim($_POST['eventLibelle'])!='') {
        $CopsEvent->setField('eventLibelle', $_POST['eventLibelle']);
        $CopsEvent->setField('categorieId', 1);
        $CopsEvent->setDateDebut($_POST['dateDebut']);
        $CopsEvent->setDateFin($_POST['dateFin']);

        if (isset($_POST['allDayEvent'])) {
          $CopsEvent->setField('allDayEvent', 1);
        } else {
          $CopsEvent->setField('allDayEvent', 0);
          $CopsEvent->setField('heureDebut', str_pad($_POST['heureDebut'], 2, '0', STR_PAD_LEFT).':'
          .str_pad($_POST['minuteDebut'], 2, '0', STR_PAD_LEFT));
          $CopsEvent->setField('heureFin', str_pad($_POST['heureFin'], 2, '0', STR_PAD_LEFT).':'
          .str_pad($_POST['minuteFin'], 2, '0', STR_PAD_LEFT));
        }

        if ($CopsEvent->isValidInterval()) {
          if (isset($_POST['repeatStatus'])) {
            $CopsEvent->setField('repeatStatus', 1);
            // repeatType
            $CopsEvent->setField('repeatType', $_POST['repeatType']);
            // repeatInterval
            $CopsEvent->setField('repeatInterval', $_POST['repeatInterval']);
            // repeatEnd
            $CopsEvent->setField('repeatEnd', $_POST['repeatEnd']);
            // repeatEndValue
            if ($_POST['repeatEnd']=='endDate') {
              $CopsEvent->setRepeatEndValue($_POST['endDateValue']);
            } elseif ($_POST['repeatEnd']=='endRepeat') {
              $CopsEvent->setField('repeatEndValue', $_POST['endRepetitionValue']);
            }
          } else {
            $CopsEvent->setField('repeatStatus', 0);
            $CopsEvent->setField('repeatType', '');
            $CopsEvent->setField('repeatInterval', '');
            $CopsEvent->setField('repeatEnd', '');
            $CopsEvent->setField('repeatEndValue', '');
          }
          $CopsEvent->saveEvent();
        } else {
          echo "Intervalle non valide";
        }
      }
    }
    */
  }

    /**
     * @since 1.22.11.25
     * @version 1.22.11.26
     */
    public function getOngletContent()
    {
        /////////////////////////////////////////
        $strButtonCreation = '';
        $classe = 'btn btn-primary mb-3 btn-block';
        if ($this->action==self::CST_WRITE) {
            /////////////////////////////////////////
            // Le bouton d'annulation.
            $href = $this->getRefreshUrl([self::CST_ACTION=>'']);
            $label = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP.self::LABEL_RETOUR;
            /////////////////////////////////////////
            $mainContent = $this->getEditContent();
        } else {
            // Le bouton de création.
            $href = $this->getRefreshUrl([self::CST_ACTION=>self::CST_WRITE]);
            $label = self::LABEL_CREER_ENTREE;
            /////////////////////////////////////////
            $mainContent = $this->getListContent();
        }
        $strButtonCreation .= $this->getLink($label, $href, $classe);
        /////////////////////////////////////////
        
        $urlTemplate = self::PF_SECTION_ONGLET;
        $attributes = [
            // L'id de la page
            'section-cal-event',
            // Le bouton éventuel de création / retour...
            $strButtonCreation,
            // Le nom du bloc du menu de gauche
            $this->titreOnglet,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since v1.22.11.26
     * @version v1.22.11.26
     * @return string
     */
    public function getEditContent()
    {
        ///////////////////////////////////////////////////////
        // Les options pour la Catégorie
        $strOptsCategorie = '';
        $objsCopsEventCategorie = $this->objCopsEventServices->getCopsEventCategories();
        while (!empty($objsCopsEventCategorie)) {
            $objCopsEventCategorie = array_shift($objsCopsEventCategorie);
            $label = $objCopsEventCategorie->getField(self::FIELD_CATEG_LIBELLE);
            $value = $objCopsEventCategorie->getField(self::FIELD_ID);
            $blnChecked = ($this->objCopsEvent->getField(self::FIELD_CATEG_ID==$value));
            $strOptsCategorie .= $this->getOption($label, $value, $blnChecked);
        }

        ///////////////////////////////////////////////////////
        // Les options pour les heures vont de 00 à 23 par pas de 1
        $strOptsHeuresDebut = '';
        $strOptsHeuresFin = '';
        for ($i=0; $i<=23; ++$i) {
            $label = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hDebut = $this->objCopsEvent->getField(self::FIELD_HEURE_DEBUT);
            $strOptsHeuresDebut .= $this->getOption($label, $i, $i==$hDebut);
            $hFin = $this->objCopsEvent->getField(self::FIELD_HEURE_FIN);
            $strOptsHeuresFin .= $this->getOption($label, $i, $i==$hFin);
        }

        ///////////////////////////////////////////////////////
        // Les options pour les minutes vont de 00 à 55 par pas de 5
        $strOptsMinutesDebut = '';
        $strOptsMinutesFin = '';
        for ($i=0; $i<=55; $i+=5) {
            $label = str_pad($i, 2, '0', STR_PAD_LEFT);
            $mDebut = $this->objCopsEvent->getField(self::FIELD_MINUTE_DEBUT);
            $strOptsMinutesDebut .= $this->getOption($label, $i, $i==$mDebut);
            $mFin = $this->objCopsEvent->getField(self::FIELD_MINUTE_FIN);
            $strOptsMinutesFin .= $this->getOption($label, $i, $i==$mFin);
        }

        ///////////////////////////////////////////////////////
        // Les options pour la Périodicité
        $strOptsPeriodicite = '';
        $arrPeriodicite = [self::CST_EVENT_RT_DAILY => self::LABEL_DAILY, self::CST_EVENT_RT_WEEKLY => self::LABEL_WEEKLY, self::CST_EVENT_RT_MONTHLY => self::LABEL_MONTHLY, self::CST_EVENT_RT_YEARLY => self::LABEL_YEARLY];
        $inputAttributes = [self::ATTR_CLASS => 'custom-control-input', self::ATTR_TYPE => 'radio', self::ATTR_NAME => self::FIELD_REPEAT_TYPE];
        $labelAttributes = [self::ATTR_CLASS => 'custom-control-label'];
        foreach ($arrPeriodicite as $key => $value) {
            $inputAttributes[self::FIELD_ID] = 'repeat_'.$key;
            $inputAttributes[self::ATTR_VALUE] = $key;
            if ($key==$this->objCopsEvent->getField(self::FIELD_REPEAT_TYPE)) {
                $inputAttributes[self::CST_CHECKED] = self::CST_CHECKED;
            }
            $strInput = $this->getBalise(self::TAG_INPUT, '', $inputAttributes);

            $labelAttributes['for'] = 'repeat_'.$key;
            $strLabel = $this->getBalise(self::TAG_LABEL, $value, $labelAttributes);

            $divAttributes = [self::ATTR_CLASS => 'custom-control custom-radio'];
            $strOptsPeriodicite .= $this->getDiv($strInput.$strLabel, $divAttributes);
        }

        ///////////////////////////////////////////////////////
        // Initialisation des booléens
        $blnIsAlldayEvent = $this->objCopsEvent->isAllDayEvent();
        $blnIsRecurEvent = $this->objCopsEvent->isRepetitive();
        // Initialisation du type de fin de répétition
        $strRepeatType = $this->objCopsEvent->getField(self::FIELD_REPEAT_TYPE);

        $urlTemplate = self::PF_FORM_EVENT;
        ///////////////////////////////////////////////////////
        // Contenu du formulaire
        $attributes = [
            // Id du Form - creerNewEvent pour une création, editerEvent pour une édition
            'creerNewEvent',
            // Id de l'événement - vide pour une création, renseigné pour une édition
            $this->objCopsEvent->getField(self::FIELD_ID),
            // Titre de l'événement
            $this->objCopsEvent->getField(self::FIELD_EVENT_LIBELLE),
            // Liste d'options pour la Catégorie
            $strOptsCategorie,
            // Checkbox Allday Event
            ($blnIsAlldayEvent ? self::CST_CHECKED : ''),
            // Date de début
            $this->objCopsEvent->getField(self::FIELD_DATE_DEBUT),
            // Affichage horaires début et fin selon checkbox Allday Event
            ($blnIsAlldayEvent ? ' style="display:none;"' : ''),
            // Liste des options d'heure début
            $strOptsHeuresDebut,
            // Liste des options de minutes début
            $strOptsMinutesDebut,
            // Date de fin
            $this->objCopsEvent->getField(self::FIELD_DATE_FIN),
            // Liste des options d'heure fin
            $strOptsHeuresFin,
            // Liste des options de minutes fin
            $strOptsMinutesFin,
            // Checkbox Récurrent Event
            ($blnIsRecurEvent ? self::CST_CHECKED : ''),
            // Affichage interface selon checkbox Récurrent Event
            ($blnIsRecurEvent ? '' : ' style="display:none;"'),
            // Liste des options de périodicité
            $strOptsPeriodicite,
            // Intervalle de répétition
            $this->objCopsEvent->getField(self::FIELD_REPEAT_INTERVAL),
            // Radio Never end
            ($strRepeatType==self::CST_EVENT_RT_NEVER ? self::CST_CHECKED : ''),
            // Radio EndDate
            ($strRepeatType==self::CST_EVENT_RT_ENDDATE ? self::CST_CHECKED : ''),
            // Valeur EndDate si renseignée
            $this->objCopsEvent->getField(self::FIELD_ENDDATE_VALUE),
            // Radio EndRepeat
            ($strRepeatType==self::CST_EVENT_RT_ENDREPEAT ? self::CST_CHECKED : ''),
            // Valeur EndRepeat si renseignée
            $this->objCopsEvent->getField(self::FIELD_REPEAT_END),
        ];
        return $this->getRender($urlTemplate, $attributes);
        ///////////////////////////////////////////////////////
    }
    
    /**
     * @since v1.22.11.26
     * @version v1.22.11.26
     * @return string
     */
    public function getListContent()
    {
        $strPagination = null;
        $urlTemplateList = self::PF_SECTION_ONGLET_LIST;
        $titre = self::LABEL_EVENTS;
        
        // On va construire le Header du tableau
        $thAttributes = [self::ATTR_CLASS => 'mailbox-name'];
        $headerContent  = $this->getTh('Titre', $thAttributes);
        $thAttributes[self::ATTR_STYLE] = 'width:150px;';
        $headerContent .= $this->getTh('Catégorie', $thAttributes);
        $headerContent .= $this->getTh('Date de début', $thAttributes);
        $headerContent .= $this->getTh('Date de fin', $thAttributes);
        $thAttributes = [self::ATTR_STYLE=>'width:60px;'];
        $headerContent .= $this->getTh('Périodicité', $thAttributes);
        $header = $this->getBalise(self::TAG_TR, $headerContent);
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objsCopsEvent = $this->objCopsEventServices->getCopsEvents();
        $listContent = '';
        if (empty($objsCopsEvent)) {
            $listContent = '<tr><td class="text-center" colspan="5">'.self::LABEL_NO_RESULT.'</td></tr>';
        } else {
            /////////////////////////////////////////////:
            // Pagination
            $strPagination = $this->buildPagination($objsCopsEvent);
            /////////////////////////////////////////////:
            foreach ($objsCopsEvent as $objCopsEvent) {
                $listContent .= $objCopsEvent->getBean()->getTableRow();
            }
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Toolbar & Pagination
        // Bouton pour recharger la liste
        $label = $this->getLink($this->getIcon(self::I_ARROWS_ROTATE), $this->getRefreshUrl(), self::CST_TEXT_WHITE);
        $btnAttributes = [self::ATTR_TITLE => self::LABEL_REFRESH_LIST];
        $strToolBar = $this->getButton($label, $btnAttributes);
        // Ajout de la pagination
        $strToolBar .= $this->getDiv($strPagination, [self::ATTR_CLASS=>'float-right']);
        /////////////////////////////////////////
        
        $listAttributes = [$titre, $strToolBar, $header, $listContent];
        return $this->getRender($urlTemplateList, $listAttributes);
    }

    /**
     * @param array $urlElements
     * @return string
     * @since v1.22.11.25
     * @version v1.22.11.25
     */
    public function getRefreshUrl($urlElements=[])
    {
        // Si curPage est défini et non présent dans $urlElements, il doit être repris.
        if ($this->curPage!='' && !isset($urlElements[self::CST_CURPAGE])) {
            $urlElements[self::CST_CURPAGE] = $this->curPage;
        }
        return $this->getUrl($urlElements);
    }
    
    /**
     * @param array $objs
     * @return string
     * @since 1.22.10.27
     * @version 1.22.10.27
     */
    public function buildPagination(&$objs)
    {
        $nbItems = count($objs);
        $nbItemsPerPage = 10;
        $nbPages = ceil($nbItems/$nbItemsPerPage);
        $strPagination = '';
        if ($nbPages>1) {
            // Le bouton page précédente
            $label = $this->getIcon('caret-left');
            if ($this->curPage!=1) {
                $btnClass = '';
                $href = $this->getRefreshUrl([self::CST_CURPAGE=>$this->curPage-1]);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = [self::ATTR_CLASS=>$btnClass];
            $strPagination .= $this->getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, [$firstItem+1, $lastItem, $nbItems]);
            
            // Le bouton page suivante
            $label = $this->getIcon('caret-right');
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $href = $this->getRefreshUrl([self::CST_CURPAGE=>$this->curPage+1]);
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = [self::ATTR_CLASS=>$btnClass];
            $strPagination .= self::CST_NBSP.$this->getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }

    /**
     * @since 1.22.11.22
     * @version 1.22.11.22
     */
    public function dealWithWriteAction()
    {
        ///////////////////////////////////////////////////////
        // On défini les champs obligatoires
        $arrFields = [self::FIELD_EVENT_LIBELLE, self::FIELD_CATEG_ID, self::FIELD_DATE_DEBUT, self::FIELD_DATE_FIN, self::FIELD_ALL_DAY_EVENT, self::FIELD_REPEAT_STATUS];
        // Si ce n'est pas un "All Day Event", il faut définir les heures et minutes de début et de fin
        if (!isset($this->urlParams[self::FIELD_ALL_DAY_EVENT])) {
            $arrExtras = [self::FIELD_HEURE_DEBUT, self::FIELD_MINUTE_DEBUT, self::FIELD_HEURE_FIN, self::FIELD_MINUTE_FIN];
            $arrFields = array_merge($arrFields, $arrExtras);
        }
        // S'il y a répétition, on doit saisir les données relatives à la répétition.
        if (isset($this->urlParams[self::FIELD_REPEAT_STATUS])) {
            $arrExtras = [self::FIELD_REPEAT_TYPE, self::FIELD_REPEAT_INTERVAL, self::FIELD_ENDDATE_VALUE, self::FIELD_REPEAT_END];
            $arrFields = array_merge($arrFields, $arrExtras);
        }

        $this->objCopsEvent = new CopsEvent();
        while (!empty($arrFields)) {
            $field = array_shift($arrFields);
            // Les dates saisies ont une assignation spécifique pour vérifier le format.
            if ($field==self::FIELD_DATE_DEBUT) {
                $this->objCopsEvent->setDateDebut(stripslashes((string) $this->urlParams[$field]));
            } elseif ($field==self::FIELD_DATE_FIN) {
                $this->objCopsEvent->setDateFin(stripslashes((string) $this->urlParams[$field]));
            } elseif ($field==self::FIELD_HEURE_DEBUT) {
                $valeur  = str_pad((string) $this->urlParams[$field], 2, '0', STR_PAD_LEFT).':';
                $valeur .= str_pad((string) $this->initVar(self::FIELD_MINUTE_DEBUT, 0), 2, '0', STR_PAD_LEFT);
                $this->objCopsEvent->setField($field, $valeur);
            } elseif ($field==self::FIELD_HEURE_FIN) {
                $valeur  = str_pad((string) $this->urlParams[$field], 2, '0', STR_PAD_LEFT).':';
                $valeur .= str_pad((string) $this->initVar(self::FIELD_MINUTE_FIN, 0), 2, '0', STR_PAD_LEFT);
                $this->objCopsEvent->setField($field, $valeur);
            } else {
                $this->objCopsEvent->setField($field, stripslashes((string) $this->urlParams[$field]));
            }
        }


        /*
        if (isset($this->urlParams[self::FIELD_REPEAT_END])) {
            if ($this->urlParams[self::FIELD_REPEAT_END]=='endDate' &&
            $this->urlParams[self::FIELD_ENDDATE_VALUE]!='') {
                $valeur = stripslashes($this->urlParams[self::FIELD_ENDDATE_VALUE]);
                $this->objCopsEvent->setRepeatEndValue($valeur);
            }
            if ($this->urlParams[self::FIELD_REPEAT_END]=='endRepeat' &&
            $this->urlParams[self::FIELD_REPEAT_END_VALUE]!='') {
                $valeur = stripslashes($this->urlParams[self::FIELD_REPEAT_END_VALUE]);
                $this->objCopsEvent->setField(self::FIELD_REPEAT_END_VALUE, $valeur);
            }
        }
        */
        ///////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////
        // Si les contrôles sont ok, on insère ou on met à jour
        if ($this->objCopsEvent->checkFields()) {
            if ($this->objCopsEvent->getField(self::FIELD_ID)=='') {
                $this->objCopsEvent->saveEvent();
                echo "[".MySQL::wpdbLastQuery()."]";
            } else {
                //$this->objCopsEventServices->updateEvent($this->objCopsEvent);
            }

            // Une fois la mise à jour ou l'insertion faite, on doit gérer les event_date relatifs à l'event.
            //
        }
        ///////////////////////////////////////////////////////
    }
}
