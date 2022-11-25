<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminCalendarEventBean
 * @author Hugues
 * @since 1.22.11.22
 * @version 1.22.11.22
 */
class WpPageAdminCalendarEventBean extends WpPageAdminCalendarBean
{
	public function __construct()
	{
		parent::__construct();
        $this->slugSubOnglet = self::CST_CAL_EVENT;
        $this->titreSubOnglet = 'Événements';
        /////////////////////////////////////////
        // Définition des services
		$this->objCopsEventServices = new CopsEventServices();
		
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination & on ajoute à l'url de Refresh
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
		
        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
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
          $CopsEvent->setField('heureDebut', str_pad($_POST['heureDebut'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['minuteDebut'], 2, '0', STR_PAD_LEFT));
          $CopsEvent->setField('heureFin', str_pad($_POST['heureFin'], 2, '0', STR_PAD_LEFT).':'.str_pad($_POST['minuteFin'], 2, '0', STR_PAD_LEFT));
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
     * @version 1.22.11.25
     */
    public function getOngletContent()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-form-event.php';
        ///////////////////////////////////////////////////////
        // Contenu du formulaire
        $attributes = array(
            // Id du Form
            'creerNewEvent',
            // Affichage du form
            'display: none;',
        );
        $strForm = $this->getRender($urlTemplate, $attributes);
        ///////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-calendar-events.php';
        ///////////////////////////////////////////////////////
        // Contenu de la page
        $strContent = '';
        $objsCopsEvent = $this->objCopsEventServices->getCopsEvents();
        while (!empty($objsCopsEvent)) {
            $objCopsEvent = array_shift($objsCopsEvent);
			$strContent .= $objCopsEvent->getBean()->getTableRow();
        }
        ///////////////////////////////////////////////////////
    
        $attributes = array(
          // Les lignes à afficher
          $strContent,
          // Formulaire
          $strForm,
        );
        $mainContent = $this->getRender($urlTemplate, $attributes);
		
        /////////////////////////////////////////
        // Le bouton de création ou d'annulation.
        $strButtonCreation = '';
        $classe = 'btn btn-primary mb-3'.($blnExtraButton ? ' col-6' : ' btn-block');
		/*
        if ($this->panel!=self::CST_LIST) {
            $label = $this->getIcon('angles-left').self::CST_NBSP.self::LABEL_RETOUR;
            $strButtonCreation .= $this->getLink($label, $this->getRefreshUrl(), $classe);
        }
		*/
        $href = $this->getRefreshUrl(array(self::CST_ACTION=>self::CST_WRITE));
        $strButtonCreation .= $this->getLink(self::LABEL_CREER_ENTREE, $href, $classe);
        /////////////////////////////////////////
		
        $urlTemplateList = 'web/pages/public/fragments/public-fragments-section-onglet-list.php';
        $titre = 'Événements';
		
        // On va construire le Header du tableau
        $thAttributes = array(
            self::ATTR_CLASS => 'mailbox-name',
        );
        $headerContent  = $this->getTh('Titre', $thAttributes);
        $thAttributes[self::ATTR_STYLE] = 'width:150px;';
        $headerContent .= $this->getTh('Catégorie', $thAttributes);
        $headerContent .= $this->getTh('Date de début', $thAttributes);
        $headerContent .= $this->getTh('Date de fin', $thAttributes);
        $thAttributes = array(self::ATTR_STYLE=>'width:60px;');
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
        $label = $this->getLink($this->getIcon('arrows-rotate'), $this->getRefreshUrl(), self::CST_TEXT_WHITE);
        $btnAttributes = array(self::ATTR_TITLE => self::LABEL_REFRESH_LIST);
        $strToolBar = $this->getButton($label, $btnAttributes);
        // Ajout de la pagination
        $strToolBar .= $this->getDiv($strPagination, array(self::ATTR_CLASS=>'float-right'));
        /////////////////////////////////////////
		
        $listAttributes = array(
            $titre,
            $strToolBar,
            $header,
            $listContent,
        );
        $mainContent = $this->getRender($urlTemplateList, $listAttributes);
		
		
        $urlTemplate = self::PF_SECTION_ONGLET;
        $attributes = array(
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
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @param array $urlElements
     * @return string
     * @since v1.22.11.25
     * @version v1.22.11.25
     */
    public function getRefreshUrl($urlElements=array())
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
                $href = $this->getRefreshUrl(array(self::CST_CURPAGE=>$this->curPage-1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= $this->getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, array($firstItem+1, $lastItem, $nbItems));
            
            // Le bouton page suivante
            $label = $this->getIcon('caret-right');
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $href = $this->getRefreshUrl(array(self::CST_CURPAGE=>$this->curPage+1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
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
        $arrFields = array(
			self::FIELD_EVENT_LIBELLE,
			self::FIELD_DATE_DEBUT,
			self::FIELD_DATE_FIN,
			self::FIELD_ALL_DAY_EVENT,
			self::FIELD_REPEAT_STATUS,
		);
		// Si ce n'est pas un "All Day Event", il faut définir les heures et minutes de début et de fin
		if (!isset($this->urlParams[self::FIELD_ALL_DAY_EVENT])) {
			$arrExtras = array(
				self::FIELD_HEURE_DEBUT,
				self::FIELD_MINUTE_DEBUT,
				self::FIELD_HEURE_FIN,
				self::FIELD_MINUTE_FIN,
			);
			$arrFields = array_merge($arrFields, $arrExtras);
		}
		// S'il y a répétition, on doit saisir les données relatives à la répétition.
		if (isset($this->urlParams[self::FIELD_REPEAT_STATUS])) {
			$arrExtras = array(
				self::FIELD_REPEAT_TYPE,
				self::FIELD_REPEAT_INTERVAL,
				self::FIELD_ENDDATE_VALUE,
				self::FIELD_REPEAT_END,
			);
			$arrFields = array_merge($arrFields, $arrExtras);
		}

		$this->objCopsEvent = new CopsEvent();
        while (!empty($arrFields)) {
            $field = array_shift($arrFields);
			// Les dates saisies ont une assignation spécifique pour vérifier le format.
			if ($field==self::FIELD_DATE_DEBUT) {
				$this->objCopsEvent->setDateDebut(stripslashes($this->urlParams[$field]));
			} elseif ($field==self::FIELD_DATE_FIN) {
				$this->objCopsEvent->setDateFin(stripslashes($this->urlParams[$field]));
			} else {
				$this->objCopsEvent->setField($field, stripslashes($this->urlParams[$field]));
			}
        }

		// TODO : Ajouter une liste déroulante sur le formulaire pour gérer la catégorie.
        $this->objCopsEvent->setField(self::FIELD_CATEG_ID, 1);
		
		if (isset($this->urlParams['repeatEnd']) && $this->urlParams['repeatEnd']=='endDate' &&
		isset($this->urlParams['endDateValue']) && $this->urlParams['endDateValue']!='') {
			$this->objCopsEvent->setRepeatEndValue(stripslashes($this->urlParams['endDateValue']));
		}
		if (isset($this->urlParams['repeatEnd']) && $this->urlParams['repeatEnd']=='endRepeat' &&
		isset($this->urlParams['endRepetitionValue']) && $this->urlParams['endRepetitionValue']!='') {
			$this->objCopsEvent->setField(self::FIELD_REPEAT_END_VALUE, stripslashes($this->urlParams['endRepetitionValue']));
		}
        ///////////////////////////////////////////////////////
		
        ///////////////////////////////////////////////////////
		// Si les contrôles sont ok, on insère ou on met à jour
        if ($this->objCopsEvent->checkFields()) {
            if ($this->objCopsEvent->getField(self::FIELD_ID)=='') {
                $this->objCopsEvent->saveEvent();
            } else {
                //$this->objCopsEventServices->updateEvent($this->objCopsEvent);
            }
			
			// Une fois la mise à jour ou l'insertion faite, on doit gérer les event_date relatifs à l'event.
			//
        }
        ///////////////////////////////////////////////////////
    }
}
