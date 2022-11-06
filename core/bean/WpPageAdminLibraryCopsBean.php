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
        $this->arrMenu = array(
            'ranked' => 'Gradés',
            'alpha' => 'A-Alpha',
            'epsilon' => 'B-Epsilon',
        );
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getSubongletContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        // Pour ça, on doit récupérer les catégories Wp qui sont des enfants de la catégorie Index.
        $menuContent = '';
        $strClass = 'nav-link text-white';
        foreach ($this->arrMenu as $key => $value) {
            $aContent = '<i class="fa-solid fa-user-police-tie"></i>'.self::CST_NBSP.$value;
            $urlElements = array(
                self::CST_SUBONGLET => self::CST_LIB_COPS,
                self::CST_CAT_SLUG => $key,
            );
            $href = $this->getUrl($urlElements);
            $liContent = $this->getLink($aContent, $href, $strClass);
            $strLiClass = 'nav-item'.($key==$this->catSlug ? ' '.self::CST_ACTIVE : '');
            $menuContent .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>$strLiClass));
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Définition du contenu. Par défaut, une présentation, sinon, filtrée selon le choix
        $mainContent = $this->getListContent();
        /////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-cops',
            // Le bouton éventuel de création / retour...
            '',
            // Le nom du bloc du menu de gauche
            'COPS',
            // La liste des éléments du menu de gauche
            $menuContent,
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    public function getListContent()
    {
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH,
            self::FIELD_GRADE => 'Capitaine'
        );
        if ($this->catSlug=='') {
            // La page par défaut
            $urlTemplate = 'web/pages/public/fragments/public-fragments-article-cops-entete.php';
            return $this->getRender($urlTemplate);
        }
        /////////////////////////////////////////
        // On va définir la liste des éléments à afficher.
        $urlTemplateList = 'web/pages/public/fragments/public-fragments-section-onglet-list.php';
        $titre = 'Gradés';
        
        // On va construire le Header du tableau
        $thAttributes = array(
            self::ATTR_STYLE => 'width:50px',
        );
        $headerContent  = $this->getTh('Masque', $thAttributes);
        $thAttributes = array(
            self::ATTR_CLASS => 'mailbox-name',
        );
        $headerContent .= $this->getTh('Matricule', $thAttributes);
        $headerContent .= $this->getTh('Nom', $thAttributes);
        $headerContent .= $this->getTh('Surnom', $thAttributes);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $listContent = '';
        if ($this->catSlug=='ranked') {
            $headerContent .= $this->getTh('Section', $thAttributes);
            // Les gradés uniquement

            // Gestion du Capitaine (potentiellement des Capitaines)
            $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
            while (!empty($objCopsPlayers)) {
                $objCopsPlayer = array_shift($objCopsPlayers);
                $listContent .= $objCopsPlayer->getBean()->getLibraryRow();
            }
            // Gestion des Lieutenants
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
            $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
            while (!empty($objCopsPlayers)) {
                $objCopsPlayer = array_shift($objCopsPlayers);
                $section = $objCopsPlayer->getField(self::FIELD_SECTION);
                if (in_array($section, array('A-Alpha', 'B-Epsilon'))) { // A terme, à supprimer
                    $listContent .= $objCopsPlayer->getBean()->getLibraryRow();
                }
            }
            /////////////////////////////////////////
        } else {
            //////////////////////////////////////////////////////////
            // Gestion du Lieutenant
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
            $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
            while (!empty($objCopsPlayers)) {
                $objCopsPlayer = array_shift($objCopsPlayers);
                $section = $objCopsPlayer->getField(self::FIELD_SECTION);
                if ($section!=$this->arrMenu[$this->catSlug]) {
                    // Si le Cops n'est pas dans la bonne section (ce serait bien de filtrer selon ce critère)
                    continue;
                }
                $listContent .= $objCopsPlayer->getBean()->getLibraryRow(false);
            }
            /////////////////////////////////////////
            
            // Gestion des Détectives
            $tsToday = self::getCopsDate('Y-m-d');
            
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Détective';
            $attributes[self::SQL_ORDER_BY] = self::FIELD_NOM;
            $objCopsPlayers = $this->CopsPlayerServices->getCopsPlayers($attributes);
            while (!empty($objCopsPlayers)) {
                $objCopsPlayer = array_shift($objCopsPlayers);
                $section = $objCopsPlayer->getField(self::FIELD_SECTION);
                if ($section!=$this->arrMenu[$this->catSlug]) {
                    // Si le Cops n'est pas dans la bonne section (ce serait bien de filtrer selon ce critère)
                    continue;
                }
                if ($objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE) <= $tsToday) {
                    $listContent .= $objCopsPlayer->getBean()->getLibraryRow(false);
                }
            }
            //////////////////////////////////////////////////////////
        }

        $listAttributes = array(
            $titre,
            '', // Pas de pagination
            $this->getBalise(self::TAG_TR, $headerContent),
            $listContent,
        );
        /////////////////////////////////////////

        return $this->getRender($urlTemplateList, $listAttributes);
    }
}
