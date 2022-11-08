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
            'rotation' => 'Rotation COPS',
            'accueil' => 'Rotation Accueil',
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
		switch ($this->catSlug) {
			case 'ranked' :
				$returned = $this->getListRanked();
				break;
			case 'alpha' :
			case 'epsilon' :
				$returned = $this->getListTeam();
				break;
			case 'rotation' :
				$returned = $this->getRotationCops();
				break;
			case 'accueil' :
				$urlTemplate = 'web/pages/public/fragments/public-fragments-card-rotation-accueil.php';
				$returned = $this->getRender($urlTemplate);
				break;
			default :
				// La page par défaut
				$urlTemplate = 'web/pages/public/fragments/public-fragments-article-cops-entete.php';
				$returned = $this->getRender($urlTemplate);
				break;
		}
		return $returned;
    }
	
	public function getHeader($blnRanked=true)
	{
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
		if ($blnRanked) {
			// Les gradés uniquement
			$headerContent .= $this->getTh('Section', $thAttributes);
		}
		return $headerContent;
	}
	
	public function getListRanked()
	{
		$urlTemplateList = 'web/pages/public/fragments/public-fragments-section-onglet-list.php';
		$titre = 'Gradés';
		
		$strHeader = $this->getBalise(self::TAG_TR, $this->getHeader());
		/////////////////////////////////////////

		$listContent = '';
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH,
        );
		
		//////////////////////////////////////////////////////////
		// Gestion du Capitaine (potentiellement des Capitaines)
		$attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Capitaine';
		$objCaptains = $this->CopsPlayerServices->getCopsPlayers($attributes);
		// Gestion des Lieutenants
		$attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
		$objLieutenants = $this->CopsPlayerServices->getCopsPlayers($attributes);
		$objCopsPlayers = array_merge($objCaptains, $objLieutenants);

		while (!empty($objCopsPlayers)) {
			$objCopsPlayer = array_shift($objCopsPlayers);
			$section = $objCopsPlayer->getField(self::FIELD_SECTION);
			if (in_array($section, array('', 'A-Alpha', 'B-Epsilon'))) { // A terme, à supprimer
				$listContent .= $objCopsPlayer->getBean()->getLibraryRow();
			}
		}
		
        $listAttributes = array(
            $titre,
            '', // Pas de pagination
            $strHeader,
            $listContent,
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplateList, $listAttributes);
	}
	
	public function getListTeam()
	{
		$urlTemplateList = 'web/pages/public/fragments/public-fragments-section-onglet-list.php';
		$titre = 'A-Alpha / B-Epsilon';
		
		$strHeader = $this->getBalise(self::TAG_TR, $this->getHeader(false));
		/////////////////////////////////////////

		$listContent = '';
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH,
        );
		
		//////////////////////////////////////////////////////////
		// Gestion du Lieutenant
		$attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
		$objLieutenants = $this->CopsPlayerServices->getCopsPlayers($attributes);
		// Gestion des Détectives
		$tsToday = self::getCopsDate('Y-m-d');
		$attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Détective';
		$attributes[self::SQL_ORDER_BY] = self::FIELD_NOM;
		$objDetectives = $this->CopsPlayerServices->getCopsPlayers($attributes);
		$objCopsPlayers = array_merge($objLieutenants, $objDetectives);
		
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

        $listAttributes = array(
            $titre,
            '', // Pas de pagination
            $strHeader,
            $listContent,
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplateList, $listAttributes);
	}

	public function getRotationCops()
	{
		$urlTemplate = 'web/pages/public/fragments/public-fragments-card-rotation-cops.php';
		
		// Si la date n'est pas définie, on récupère celle du jour.
		// Sinon, on récupère la date passée en paramètre.
		list($dDate, $mDate, $yDate) = explode('-', $this->initVar('strDate', $this->getCopsDate('d-m-Y')));
		$tsNow = mktime(0, 0, 0, $mDate, $dDate, $yDate);
		
		// Si la date en question n'est pas un lundi, on récupère le lundi précédent.
		$nDay = date('N', $tsNow);
		$dAjust = ($nDay!=1 ? $nDay-1 : 0);

		// Header - 4 semaines concernées
		$strHeader = '';
		for ($i=0; $i<4; $i++) {
			$strDate = date('d-m-Y', mktime(0, 0, 0, $mDate, $dDate+$dAjust+$i*7, $yDate));
			$strHeader .= $this->getDiv($strDate, array(self::ATTR_CLASS=>'col-3 table-bordered'));
		}

		// Bouton précédent
		$prevDate = date('d-m-Y', mktime(0, 0, 0, $mDate, $dDate+$dAjust-7, $yDate));
		$aContent = $this->getIcon('caret-left');
		$href = $this->getUrl(array(self::CST_CAT_SLUG=>'rotation', 'strDate'=>$prevDate));
		$btnContent = $this->getLink($aContent, $href, self::CST_TEXT_WHITE);
		$prevBtn  = $this->getButton($btnContent, array(self::ATTR_CLASS=>self::CST_TEXT_WHITE));
		$prevBtn .= self::CST_NBSP.$prevDate;
		
		// Bouton suivant
		$nextDate = date('d-m-Y', mktime(0, 0, 0, $mDate, $dDate+$dAjust+7, $yDate));
		$aContent = $this->getIcon('caret-right');
		$href = $this->getUrl(array(self::CST_CAT_SLUG=>'rotation', 'strDate'=>$nextDate));
		$btnContent = $this->getLink($aContent, $href, self::CST_TEXT_WHITE);
		$nextBtn  = $nextDate.self::CST_NBSP;
		$nextBtn .= $this->getButton($btnContent, array(self::ATTR_CLASS=>self::CST_TEXT_WHITE));
		
		// Décalage de semaines
		$tsStart = mktime(0, 0, 0, 6, 3, 2030);
		$nbDiffWeeks = (($tsNow-$tsStart)/60/60/24/7)%4;
		if ($nbDiffWeeks<0) {
		    $nbDiffWeeks = 4+$nbDiffWeeks;
		}
        
		// Roulement des équipes
		$arrSections = array(
            'A' => array('Alpha', 'Beta', 'Gamma', self::CST_NBSP),    
            'B' => array('Epsilon', 'Theta', 'Mu', 'Delta'),
		    'C' => array('Sigma', 'Tau', self::CST_NBSP, 'Pi'),
		);
		for ($i=0; $i<$nbDiffWeeks; $i++) {
		    $strSection = array_pop($arrSections['A']);
		    array_unshift($arrSections['A'], $strSection);
		    $strSection = array_pop($arrSections['B']);
		    array_unshift($arrSections['B'], $strSection);
		    $strSection = array_pop($arrSections['C']);
		    array_unshift($arrSections['C'], $strSection);
		}
		
		$divAttributes = array(self::ATTR_CLASS=>'col-1 table-bordered');
		$strRowA = '';
		$strRowB = '';
		$strRowC = '';
		for ($i=0; $i<12; $i++) {
		    $strRowA .= $this->getDiv($arrSections['A'][$i%4], $divAttributes);
		    $strRowB .= $this->getDiv($arrSections['B'][$i%4], $divAttributes);
		    $strRowC .= $this->getDiv($arrSections['C'][$i%4], $divAttributes);
		}
		
		$attributes = array(
			$strHeader,
			$prevBtn,
			$nextBtn,
		    $strRowA,
		    $strRowB,
		    $strRowC,
		);
		return $this->getRender($urlTemplate, $attributes);
	}
}