<?php
namespace core\bean;

use core\bean\UtilitiesBean;
use core\domain\CopsEventClass;
use core\services\CopsEventServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe AdminPageCalendarEventBean
 * @author Hugues
 * @since v1.23.05.15
 * @version v1.23.06.04
 */
class AdminPageCalendarEventBean extends AdminPageCalendarBean
{
    /**
     * @since v1.23.05.15
     * @version v1.23.05.28
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
        // Si writeAction est défini, par formulaire pour Write, par url pour Delete
        $writeAction = static::initVar(self::CST_WRITE_ACTION);
        if ($writeAction==self::CST_WRITE) {
            $this->dealWithWriteAction();
        } elseif ($writeAction==self::CST_DELETE) {
            $this->dealWithDeleteAction();
        } else {
            // TODO : vraiment relou cette obligation de else
        }
        /////////////////////////////////////////

        // Récupération des onglets de navigation.
        $strNavigation = $this->getContentPage();

        // Construction du Breadcrumbs
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT
        ];
        $strLink = HtmlUtils::getLink(self::LABEL_EVENTS, UrlUtils::getAdminUrl($urlAttributes));
        $this->strBreadcrumbs .= $this->getBalise(self::TAG_LI, $strLink, [self::ATTR_CLASS=>$this->styleBreadCrumbs]);

        // Construction et renvoi du template
        $attributes = [
            $this->strBreadcrumbs,
            $strNavigation,
            $this->getCard(),
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
        } elseif ($this->action==self::CST_DELETE) {
            return $this->getDeleteContent();
        } else {
            return $this->getListContent();
        }
    }

    /**
     * @since v1.23.05.25
     * @version v1.23.05.28
     */
    public function getDeleteContent(): string
    {
        $objCopsEventServices = new CopsEventServices();
        $objCopsEvent = $objCopsEventServices->getEvent($this->id);

        $msgAttributes = [$objCopsEvent->getField(self::FIELD_EVENT_LIBELLE), $this->id];
        $strMessage  = vsprintf(self::DYN_DELETE_EVENT, $msgAttributes);
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::CST_WRITE_ACTION => self::CST_DELETE,
            self::FIELD_ID => $this->id,
        ];
        $urlSuppression = UrlUtils::getAdminUrl($urlAttributes);

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CALENDAR_DELETE;
        $attributes = [
            ////////////////////////////////////////////////////
            // Titre
            self::LABEL_DELETE_EVENT,
            // Libellé
            $strMessage,
            ////////////////////////////////////////////////////
            // Url d'annulation de la suppression - 2
            $urlAnnulation,
            // Url de cofirmation de suppression de l'événement
            $urlSuppression,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.06.04
     */
    public function getEditContent(): string
    {
        $this->objCopsEventServices = new CopsEventServices();
        $this->objCopsEvent = $this->objCopsEventServices->getEvent($this->id);

        $blnIsRepetitiveEvent = $this->objCopsEvent->isRepetitive();

        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::CST_CURPAGE => $this->curPage,
        ];
        $urlAnnulation = UrlUtils::getAdminUrl($urlAttributes);
        $urlAttributes = [
            self::CST_ONGLET => self::ONGLET_CALENDAR,
            self::CST_SUBONGLET => self::CST_CAL_EVENT,
            self::CST_ACTION => self::CST_DELETE,
            self::FIELD_ID => $this->id,
        ];
        $urlSuppression = UrlUtils::getAdminUrl($urlAttributes);

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CALENDAR_EVENT_EDIT;
        $attributes = [
            ////////////////////////////////////////////////////
            // Block Categ
            $this->getEditCategBlock(),
            // Block Dates
            $this->getEditDateBlock(),
            ////////////////////////////////////////////////////
            // Checked si événement récurrent
            $blnIsRepetitiveEvent ? self::CST_CHECKED : '',
            // Visible si événement réccurent, caché sinon
            $blnIsRepetitiveEvent ? '' : ' style="display: none;"',
            ////////////////////////////////////////////////////
            // Block Period
            $this->getEditRepeatTypeBlock(),
            // Block Interval
            $this->getEditRepeatIntervalBlock(),
            // Block Custom
            $this->getEditCustomBlock(),
            ////////////////////////////////////////////////////
            // Url d'annulation de l'édition
            $urlAnnulation,
            // Url de suppression de l'événement
            $urlSuppression,
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
     * @since v1.23.05.25
     * @version v1.23.05.28
     */
    public function dealWithDeleteAction(): void
    {
        $objEventServices = new CopsEventServices();
        $objEventServices->deleteEvent([self::FIELD_ID=>$this->id]);
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.06.04
     */
    public function dealWithWriteAction(): void
    {
        $objEvent = new CopsEventClass();
        $objCopsEventServices = new CopsEventServices();

        $eventLibelle = stripslashes(static::fromPost(self::FIELD_EVENT_LIBELLE, false));
        $objEvent->setField(self::FIELD_EVENT_LIBELLE, $eventLibelle);
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

        //////
        // Valeurs par défaut pour les champs en rapport avec la répétition de l'événement
        $objEvent->setField(self::FIELD_CUSTOM_EVENT, 0);
        $objEvent->setField(self::FIELD_CUSTOM_DAY, 0);
        $objEvent->setField(self::FIELD_CUSTOM_DAY_WEEK, 0);
        $objEvent->setField(self::FIELD_CUSTOM_MONTH, 0);
        $objEvent->setField(self::FIELD_REPEAT_TYPE, '');
        $objEvent->setField(self::FIELD_REPEAT_INTERVAL, 0);
        $objEvent->setField(self::FIELD_REPEAT_END, '');
        $objEvent->setField(self::FIELD_REPEAT_END_VALUE, '');
        //////

        $repeatStatus = static::fromPost(self::FIELD_REPEAT_STATUS, 0);
        $objEvent->setField(self::FIELD_REPEAT_STATUS, $repeatStatus);

        if ($repeatStatus==1) {
            $objEvent->setField(self::FIELD_REPEAT_TYPE, static::fromPost(self::FIELD_REPEAT_TYPE));
            $objEvent->setField(self::FIELD_REPEAT_INTERVAL, static::fromPost(self::FIELD_REPEAT_INTERVAL));
            $repeatEnd = static::fromPost(self::FIELD_REPEAT_END);
            $objEvent->setField(self::FIELD_REPEAT_END, $repeatEnd);
            if ($repeatEnd==self::CST_EVENT_RT_ENDDATE) {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, static::fromPost(self::FIELD_ENDDATE_VALUE));
            } elseif ($repeatEnd==self::CST_EVENT_RT_ENDREPEAT) {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, static::fromPost('endRepetitionValue'));
            } else {
                $objEvent->setField(self::FIELD_REPEAT_END_VALUE, '');
            }

            $customEvent = static::fromPost(self::FIELD_CUSTOM_EVENT);
            if ($customEvent=='custom') {
                $objEvent->setField(self::FIELD_CUSTOM_EVENT, 1);
                $objEvent->setField(self::FIELD_CUSTOM_DAY, static::fromPost(self::FIELD_CUSTOM_DAY));
                $objEvent->setField(self::FIELD_CUSTOM_DAY_WEEK, static::fromPost(self::FIELD_CUSTOM_DAY_WEEK));
                $objEvent->setField(self::FIELD_CUSTOM_MONTH, static::fromPost(self::FIELD_CUSTOM_MONTH));
            }
        }
        
        if ($objEvent->checkFields()) {
            $id = static::fromPost(self::FIELD_ID, 0);
            if ($id==0) {
                $objCopsEventServices->insertEvent($objEvent);
            } else {
                $objEvent->setField(self::FIELD_ID, $id);
                $objCopsEventServices->updateEvent($objEvent);
            }
        } else {
            // TODO : Gestion de l'erreur ?
        }
    }

    /**
     * @since v1.23.05.29
     * @version v1.23.06.04
     */
    public function getEditCustomBlock(): string
    {
        $eligibleBlock = $this->objCopsEvent->getField(self::FIELD_CUSTOM_EVENT)==1;

        $optionDay = $this->objCopsEvent->getField(self::FIELD_CUSTOM_DAY);
        $strOptionsDay = '';
        $customDay = [1 => '1er', 2 => '2è', 3 => '3è', 4 => '4è', -1 => 'Dernier'];
        foreach ($customDay as $key => $value) {
            $strOptionsDay .= HtmlUtils::getOption($value, $key, $key==$optionDay);
        }

        $optionDayWeek = $this->objCopsEvent->getField(self::FIELD_CUSTOM_DAY_WEEK);
        $strOptionsDayWeek = '';
        foreach (DateUtils::$arrFullDays as $key => $value) {
            $strOptionsDayWeek .= HtmlUtils::getOption($value, $key, $key==$optionDayWeek);
        }

        $optionMonth = $this->objCopsEvent->getField(self::FIELD_CUSTOM_MONTH);
        $strOptionsMonth = '';
        foreach (DateUtils::$arrFullMonths as $key => $value) {
            $strOptionsMonth .= HtmlUtils::getOption($value, $key, $key==$optionMonth);
        }

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CAL_EVT_EDIT_CUSTOM;
        $attributes = [
            ////////////////////////////////////////////////////
            // customEvent checked ?
            $eligibleBlock ? self::CST_CHECKED : '',
            // customday readonly ?
            $eligibleBlock ? '' : ' '.self::CST_DISABLED,
            // Options de customday
            $strOptionsDay,
            // customdayweek readonly ?
            $eligibleBlock ? '' : ' '.self::CST_DISABLED,
            // Options de customdayweek
            $strOptionsDayWeek,
            // custommonth readonly ?
            $eligibleBlock ? '' : ' '.self::CST_DISABLED,
            // Options de custommonth
            $strOptionsMonth,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @since v1.23.05.29
     * @version v1.23.06.04
     */
    public function getEditRepeatIntervalBlock(): string
    {
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

        $strRepeatEnd = $this->objCopsEvent->getField(self::FIELD_REPEAT_END);
        $strRepeatEndValue = $this->objCopsEvent->getField(self::FIELD_REPEAT_END_VALUE);
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
        $urlTemplate = self::WEB_PA_CAL_EVT_EDIT_REPINT;
        $attributes = [
            ////////////////////////////////////////////////////
            // Valeur intervalle de répétition
            $this->objCopsEvent->getField(self::FIELD_REPEAT_INTERVAL),
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
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.29
     * @version v1.23.06.04
     */
    public function getEditRepeatTypeBlock(): string
    {
        $strRepeatType = $this->objCopsEvent->getField(self::FIELD_REPEAT_TYPE);

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CAL_EVT_EDIT_REPTYPE;
        $attributes = [
            ////////////////////////////////////////////////////
            // Daily checked ?
            $strRepeatType==self::CST_EVENT_RT_DAILY ? self::CST_CHECKED : '',
            // Weekly checked ?
            $strRepeatType==self::CST_EVENT_RT_WEEKLY ? self::CST_CHECKED : '',
            // Monthly checked ?
            $strRepeatType==self::CST_EVENT_RT_MONTHLY ? self::CST_CHECKED : '',
            // Yearly checked ?
            $strRepeatType==self::CST_EVENT_RT_YEARLY ? self::CST_CHECKED : '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.29
     * @version v1.23.06.04
     */
    public function getEditDateBlock(): string
    {
        /////////////////////////////////////////////////////////////////////////
        // Gestion si événément AllDay
        if ($this->objCopsEvent->isAllDayEvent()) {
            $strAllDayChecked = self::CST_CHECKED;
            $strHeureDebut = '';
            $strHeureFin = '';
            $strAllDayReadonlyRequired = self::CST_READONLY;
        } else {
            $strAllDayChecked = '';
            $strHeureDebut = $this->objCopsEvent->getField(self::FIELD_HEURE_DEBUT);
            $strHeureFin = $this->objCopsEvent->getField(self::FIELD_HEURE_FIN);
            $strAllDayReadonlyRequired = self::CST_REQUIRED;
        }

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CAL_EVT_EDIT_DATE;
        $attributes = [
            ////////////////////////////////////////////////////
            // Date de début
            $this->objCopsEvent->getField(self::FIELD_DATE_DEBUT),
            // Date de fin
            $this->objCopsEvent->getField(self::FIELD_DATE_FIN),
            // Checked si événement allday
            $strAllDayChecked,
            // Heure de début, Si événement allday nul
            $strHeureDebut,
            // Heure de fin, Si événement allday nul
            $strHeureFin,
            // Si événement allday readonly, sinon required
            $strAllDayReadonlyRequired,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.05.29
     * @version v1.23.06.04
     */
    public function getEditCategBlock(): string
    {
        //////////////////////////////////////////////////////////
        // Construction du menu des catégories
        $strOptions = '';
        $objsCopsEventCategorie = $this->objCopsEventServices->getEventCategories();

        $categId = $this->objCopsEvent->getField(self::FIELD_CATEG_ID);
        while (!empty($objsCopsEventCategorie)) {
            $objEventCategorie = array_shift($objsCopsEventCategorie);
            $strLibelle = $objEventCategorie->getField(self::FIELD_CATEG_LIBELLE);
            $strId = $objEventCategorie->getField(self::FIELD_ID);
            $strOptions .= HtmlUtils::getOption($strLibelle, $strId, $strId==$categId);
        }

        //////////////////////////////////////////////////////////
        $urlTemplate = self::WEB_PA_CAL_EVT_EDIT_CATEG;
        $attributes = [
            ////////////////////////////////////////////////////
            // Id
            $this->objCopsEvent->getField(self::FIELD_ID),
            // Libellé
            $this->objCopsEvent->getField(self::FIELD_EVENT_LIBELLE),
            // Select des Catégories
            $strOptions,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
