<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminLibraryCopsBean
 * @author Hugues
 * @since 1.22.11.05
 * @version 1.22.11.05
 */
class WpPageAdminLibraryCopsBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objCopsStageServices = new CopsStageServices();
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getSubongletContent()
    {
        //////////////////////////////////////////////////////////
        // Gestion du Capitaine (potentiellement des Capitaines)
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH,
            self::FIELD_GRADE => 'Capitaine'
        );
        $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
        $strContentCaptains = '';
        while (!empty($objCopsPlayers)) {
            $objCopsPlayer = array_shift($objCopsPlayers);
            $strContentCaptains .= $objCopsPlayer->getBean()->getLibraryCard();
        }
        //////////////////////////////////////////////////////////
        
        //////////////////////////////////////////////////////////
        // Gestion des Lieutenants
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
        $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
        $strContentLieutenants = '';
        while (!empty($objCopsPlayers)) {
            $objCopsPlayer = array_shift($objCopsPlayers);
            $section = $objCopsPlayer->getField(self::FIELD_SECTION);
            if (in_array($section, array('A-Alpha', 'B-Epsilon'))) {
                $divContent = $objCopsPlayer->getBean()->getLibraryCard();
                $divAttributes = array(self::ATTR_CLASS=>'col-12 col-md-4');
                $strContentLieutenants .= $this->getBalise(self::TAG_DIV, $divContent, $divAttributes);
            }
        }
        //////////////////////////////////////////////////////////
        
        //////////////////////////////////////////////////////////
        // Gestion des Détectives
        $tsToday = self::getCopsDate('Y-m-d');
        
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Détective';
        $attributes[self::SQL_ORDER_BY] = self::FIELD_NOM;
        $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
        $strContentDetectives = '';
        while (!empty($objCopsPlayers)) {
            $objCopsPlayer = array_shift($objCopsPlayers);
            if ($objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE) <= $tsToday) {
                $divContent = $objCopsPlayer->getBean()->getLibraryCard();
                $divAttributes = array(self::ATTR_CLASS=>'col-12 col-md-4');
                $strContentDetectives .= $this->getBalise(self::TAG_DIV, $divContent, $divAttributes);
            }
        }
        //////////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-library-cops.php';
        $attributes = array(
            // Les capitaines
            $strContentCaptains,
            // Les lieutenants
            $strContentLieutenants,
            // Les détectives
            $strContentDetectives,
            // Normalement, plus rien après
            '', '', '', '', '', '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
