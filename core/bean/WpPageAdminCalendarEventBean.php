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
        // Enrichissement du Breadcrumbs
        $spanAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $buttonContent = $this->getBalise(self::TAG_SPAN, $this->titreSubOnglet, $spanAttributes);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
        
        if (isset($_POST) && isset($this->urlParams[self::CST_WRITE_ACTION])) {
            $this->dealWithWriteAction();
        }
    }

    /**
     * @since 1.22.06.09
     * @version 1.22.09.21
     */
    public function getOngletContent()
    {
        $urlTemplate = self::PF_FORM_EVENT;
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
        
        $urlTemplate = self::PF_SECTION_CAL_EVENTS;
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
        
        $urlTemplate = self::PF_SECTION_ONGLET;
        $attributes = array(
            // L'id de la page
            'section-cal-event',
            // Le bouton éventuel de création / retour...
            '',//$strButtonRetour,
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
            $newValue = stripslashes($this->urlParams['endRepetitionValue']);
            $this->objCopsEvent->setField(self::FIELD_REPEAT_END_VALUE, $newValue);
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
