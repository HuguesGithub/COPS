<?php
namespace core\bean;

use core\bean\UtilitiesBean;
use core\domain\CopsEventClass;
use core\services\CopsEventServices;
use core\utils\UrlUtils;

/**
 * Classe AdminPageCalendarEventBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.05.21
 */
class AdminPageCalendarEventBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getContentOnglet(): string
    {
        /////////////////////////////////////////
        // On initialise l'éventuelle pagination, l'action ou l'id de l'événement concerné
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        $this->action  = $this->initVar(self::CST_ACTION);
        $this->id      = $this->initVar(self::FIELD_ID, 0);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Si on a une action de formulaire qui est soumise
        // TODO : envisage un fromGet pour une suppression ?
        if (static::fromPost(self::CST_WRITE_ACTION)!='') {
            $this->dealWithWriteAction();
        }
        /////////////////////////////////////////

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT
        ];
        $strLink = $this->getLink(self::LABEL_EVENTS, UrlUtils::getAdminUrl($urlAttributes), '');
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);

        // Récupération du contenu principal
        $strCards = $this->getCard();

        // Construction et renvoi du template
        $attributes = [
            $this->strBreadcrumbs,
            $strNavigation,
            $strCards,
        ];
        return $this->getRender(self::WEB_PA_CALENDAR, $attributes);
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getCard(): string
    {
        if ($this->action==self::CST_WRITE) {
            return $this->getEditContent();
        } else {
            return $this->getListContent();
        }
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getEditContent(): string
    {
        $objCopsEventServices = new CopsEventServices();
        $objCopsEvent = $objCopsEventServices->getEvent($this->id);

        $strOptions = '';
        $objsCopsEventCategorie = $objCopsEventServices->getEventCategories();
        $objBean = new UtilitiesBean();

        $categId = $objCopsEvent->getField(self::FIELD_CATEG_ID);
        while (!empty($objsCopsEventCategorie)) {
            $objEventCategorie = array_shift($objsCopsEventCategorie);
            $strLibelle = $objEventCategorie->getField(self::FIELD_CATEG_LIBELLE);
            $strId = $objEventCategorie->getField(self::FIELD_ID);
            $optAttributes = [
                self::ATTR_VALUE => $strId,
            ];
            if ($strId==$categId) {
                $optAttributes[self::CST_SELECTED] = self::CST_SELECTED;
            }
            $strOptions .= $objBean->getBalise(self::TAG_OPTION, $strLibelle, $optAttributes);
        }

        /////////////////////////////////////////////////////////////////////////
        // Gestion si événément AllDay
        if ($objCopsEvent->isAllDayEvent()) {
            $strAllDayChecked = self::CST_CHECKED;
            $strHeureDebut = '';
            $strHeureFin = '';
            $strAllDayReadonlyRequired = self::CST_READONLY;
        } else {
            $strAllDayChecked = '';
            $strHeureDebut = $objCopsEvent->getField(self::FIELD_HEURE_DEBUT);
            $strHeureFin = $objCopsEvent->getField(self::FIELD_HEURE_FIN);
            $strAllDayReadonlyRequired = self::CST_REQUIRED;
        }

        $blnIsRepetitiveEvent = $objCopsEvent->isRepetitive();
        $strRepeatType = $objCopsEvent->getField(self::FIELD_REPEAT_TYPE);

        /////////////////////////////////////////////////////////////////////////
        // Gestion de la fin de l'événement répété
        // Initialisation par défaut
        $strNeverChecked = '';
        $strEndDateChecked = '';
        $strEndDateValue = '';
        $strEndDate = self::CST_READONLY;
        $strEndRepeatChecked = '';
        $strEndRepeatValue = '';
        $strEndRepeat = self::CST_READONLY;

        $strRepeatEnd = $objCopsEvent->getField(self::FIELD_REPEAT_END);
        $strRepeatEndValue = $objCopsEvent->getField(self::FIELD_REPEAT_END_VALUE);
        // Initialisation selon sélection
        if ($strRepeatEnd==self::CST_EVENT_RT_NEVER) {
            $strNeverChecked = self::CST_CHECKED;
        } elseif ($strRepeatEnd==self::CST_EVENT_RT_ENDDATE) {
            $strEndDateChecked = self::CST_CHECKED;
            $strEndDateValue = $strRepeatEndValue;
            $strEndDate = '';
        } elseif ($strRepeatEnd==self::CST_EVENT_RT_ENDREPEAT) {
            $strEndRepeatChecked = self::CST_CHECKED;
            $strEndRepeatValue = $strRepeatEndValue;
            $strEndRepeat = '';
        } else {
            // TODO
        }

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CALENDAR_EVENT_EDIT;
        $attributes = [
            ////////////////////////////////////////////////////
            // Id - 1
            $objCopsEvent->getField(self::FIELD_ID),
            // Libellé
            $objCopsEvent->getField(self::FIELD_EVENT_LIBELLE),
            // Select des Catégories
            $strOptions,
            ////////////////////////////////////////////////////
            // Date de début - 4
            $objCopsEvent->getField(self::FIELD_DATE_DEBUT),
            // Date de fin
            $objCopsEvent->getField(self::FIELD_DATE_FIN),
            // Checked si événement allday
            $strAllDayChecked,
            // Heure de début, Si événement allday nul
            $strHeureDebut,
            // Heure de fin, Si événement allday nul
            $strHeureFin,
            // Si événement allday readonly, sinon required
            $strAllDayReadonlyRequired,
            ////////////////////////////////////////////////////
            // Checked si événement récurrent - 10
            $blnIsRepetitiveEvent ? self::CST_CHECKED : '',
            // Visible si événement réccurent, caché sinon
            $blnIsRepetitiveEvent ? '' : ' style="display: none;"',
            // Daily checked ?
            $strRepeatType==self::CST_EVENT_RT_DAILY ? self::CST_CHECKED : '',
            // Weekly checked ?
            $strRepeatType==self::CST_EVENT_RT_WEEKLY ? self::CST_CHECKED : '',
            // Monthly checked ?
            $strRepeatType==self::CST_EVENT_RT_MONTHLY ? self::CST_CHECKED : '',
            // Yearly checked ?
            $strRepeatType==self::CST_EVENT_RT_YEARLY ? self::CST_CHECKED : '',
            // Custom checked ?
            $strRepeatType==self::CST_EVENT_RT_CUSTOM ? self::CST_CHECKED : '',
            ////////////////////////////////////////////////////
            // Valeur intervalle de répétition - 17
            $objCopsEvent->getField(self::FIELD_REPEAT_INTERVAL),
            // Never checked
            $strNeverChecked,
            // EndDate checked
            $strEndDateChecked,
            // EndDateValue
            $strEndDateValue,
            // EndDate checked ?
            $strEndDate,
            // EndRepeat checked
            $strEndRepeatChecked,
            // EndRepeatValue
            $strEndRepeatValue,
            // EndRepeat checked ?
            $strEndRepeat,
            ////////////////////////////////////////////////////
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function getListContent(): string
    {
        // TODO : reprendre la méthode originelle pour traiter notamment la pagination.
        $objCopsEventServices = new CopsEventServices();
        $objsCopsEvent = $objCopsEventServices->getEvents([]);
        $strTrs = '';

        while (!empty($objsCopsEvent)) {
            $objCopsEvent = array_shift($objsCopsEvent);
            $strTrs .= $objCopsEvent->getBean()->getTableRow();
        }

        $urlElements = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::CST_ACTION => self::CST_WRITE,
        ];

        $urlTemplate = self::WEB_PA_CALENDAR_EVENT_LIST;
        $attributes = [
            // La liste des événements
            $strTrs,
            // Le lien pour créer un nouvel événement.
            UrlUtils::getAdminUrl($urlElements),
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.05.21
     */
    public function dealWithWriteAction(): void
    {
        $objEvent = new CopsEventClass();
        $objCopsEventServices = new CopsEventServices();

        $objEvent->setField(self::FIELD_EVENT_LIBELLE, static::fromPost(self::FIELD_EVENT_LIBELLE, false));
        $objEvent->setField(self::FIELD_CATEG_ID, static::fromPost(self::FIELD_CATEG_ID));
        $objEvent->setField(self::FIELD_DATE_DEBUT, static::fromPost(self::FIELD_DATE_DEBUT));
        $objEvent->setField(self::FIELD_DATE_FIN, static::fromPost(self::FIELD_DATE_FIN));

        $allDayEvent = static::fromPost(self::FIELD_ALL_DAY_EVENT, 0);
        $objEvent->setField(self::FIELD_ALL_DAY_EVENT, $allDayEvent);
        if ($allDayEvent==1) {
            $objEvent->setField(self::FIELD_HEURE_DEBUT, '');
            $objEvent->setField(self::FIELD_HEURE_FIN, '');
        } else {
            $objEvent->setField(self::FIELD_HEURE_DEBUT, static::fromPost(self::FIELD_HEURE_DEBUT));
            $objEvent->setField(self::FIELD_HEURE_FIN, static::fromPost(self::FIELD_HEURE_FIN));
        }

        $repeatStatus = static::fromPost(self::FIELD_REPEAT_STATUS, 0);
        $objEvent->setField(self::FIELD_REPEAT_STATUS, $repeatStatus);
        if ($repeatStatus==1) {
            $objEvent->setField(self::FIELD_REPEAT_TYPE, static::fromPost(self::FIELD_REPEAT_TYPE));
            $objEvent->setField(self::FIELD_REPEAT_INTERVAL, static::fromPost(self::FIELD_REPEAT_INTERVAL));
            $repeatEnd = static::fromPost(self::FIELD_REPEAT_END);
            $objEvent->setField(self::FIELD_REPEAT_END, $repeatEnd);
            if ($repeatEnd==self::CST_EVENT_RT_ENDDATE) {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, static::fromPost('endDateValue'));
            } elseif ($repeatEnd==self::CST_EVENT_RT_ENDREPEAT) {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, static::fromPost('endRepetitionValue'));
            } else {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, '');
            }
        } else {
            $objEvent->setField(self::FIELD_REPEAT_TYPE, '');
            $objEvent->setField(self::FIELD_REPEAT_INTERVAL, 0);
            $objEvent->setField(self::FIELD_REPEAT_END, '');
            $objEvent->setField(self::FIELD_REPEAT_END_VALUE, '');
        }
        
        if ($objEvent->checkFields()) {
            $id = static::fromPost(self::FIELD_ID, 0);
            if ($id==0) {
                $objCopsEventServices->insert($objEvent);
            } else {
                $objEvent->setField(self::FIELD_ID, $id);
                $objCopsEventServices->updateEvent($objEvent);
            }
        } else {
            // TODO : Gestion de l'erreur ?
        }
    }

     /*
    public function __construct()
    {

        /////////////////////////////////////////
        // Enrichissement du Breadcrumbs
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////

        if (static::fromPost(self::CST_WRITE_ACTION)!='') {
            $this->dealWithWriteAction();
        }

        /*

    if (isset($_POST) && !empty($_POST)) {
      $CopsEvent = new CopsEvent();

      if (trim($_POST['eventLibelle'])!='') {


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
    * /
  }
    */

    /**
     * @since 1.22.11.25
     * @version 1.22.11.26
     *
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
     *
    public function getListContent()
    {
        $strPagination = null;
        
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
     * @param array $objs
     * @return string
     * @since 1.22.10.27
     * @version 1.22.10.27
     *
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
    */
}
