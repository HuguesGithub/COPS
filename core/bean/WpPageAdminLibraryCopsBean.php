<?php
namespace core\bean;

use core\services\CopsStageServices;
use core\utils\DateUtils;
use core\utils\HtmlUtils;

/**
 * Classe WpPageAdminLibraryCopsBean
 * @author Hugues
 * @since 1.22.11.05
 * @version v1.23.05.28
 */
class WpPageAdminLibraryCopsBean extends WpPageAdminLibraryBean
{
    public function __construct()
    {
        parent::__construct();
        // On initialise les services
        $this->objCopsStageServices = new CopsStageServices();
        // Une variable parmi d'autres (hauteur de lignes dans la rotation des AC
        $this->lineHeight = 14;

        // On défini le menu latéral gauche
        $this->arrMenu = [
            'ranked' => 'Gradés',
            'alpha' => 'A-Alpha',
            'beta' => 'A-Beta',
            'epsilon' => 'B-Epsilon',
            'rotation' => 'Rotation COPS',
            'accueil' => 'Rotation Accueil'
        ];

        // Le tableau des auxiliaires civils va tourner en fonction de la semaine.
        // Puisqu'il sont 7, on a 7 configurations possibles.
        $this->arrAuxiliairesCivils = [
            '0' => 'ANDRES Guillermo',
            'ASHLEY Ruby',
            'FIELDS Sonia',
            'COOK James',
            'JENNINGS Crystal',
            'MARRERO Rose',
            'TYLER Ramona'
        ];
        
        // La rotation des Auxiliaires Civils
        $this->arrRotationsAC = [
            'Mon' => [[2], [1, 3, 4], [5]],
            'Tue' => [[2], [0, 1, 3], [4]],
            'Wed' => [[5], [0, 1, 6], [2]],
            'Thu' => [[3], [0, 4, 6], [5]],
            'Fri' => [[2], [3, 4, 6], [5]],
            'W-e' => [[0], [1]]
        ];
        
        // Les officiers de police présents à l'accueil de l'étage du COPS
        $this->arrOfficiersPolice = [
            'A' => 'McLURE Humphrey',
            'B' => 'HARRY Lise',
            'C' => 'BRADLEY Robert',
            'D' => 'FULLERTON Brad',
            'E' => 'RIVERO Hector',
            'F' => 'ROSHAN Lynda'
        ];
        
        // La rotation des Officiers de Police
        $this->arrRotationsOP = [
            0 => [
                'Mon' => ['am'=>['C'], 'noon'=>['A', 'B'], 'pm'=>['D']],
                'Tue' => ['am'=>['E'], 'noon'=>['A', 'B'], 'pm'=>['F']],
                'Wed' => ['am'=>['C'], 'noon'=>['B', 'D'], 'pm'=>['A']],
                'Thu' => ['am'=>['E'], 'noon'=>['A', 'F'], 'pm'=>['C']],
                'Fri' => ['am'=>['D'], 'noon'=>['B', 'E'], 'pm'=>['F']],
                'W-e' => ['am'=>['A'], 'pm'=>['B']]
            ],
            1 => [
                'Mon' => ['am'=>['C'], 'noon'=>['A', 'B'], 'pm'=>['D']],
                'Tue' => ['am'=>['E'], 'noon'=>['A', 'B'], 'pm'=>['F']],
                'Wed' => ['am'=>['C'], 'noon'=>['A', 'D'], 'pm'=>['B']],
                'Thu' => ['am'=>['E'], 'noon'=>['B', 'F'], 'pm'=>['C']],
                'Fri' => ['am'=>['D'], 'noon'=>['A', 'E'], 'pm'=>['F']],
                'W-e' => ['am'=>['C'], 'pm'=>['D']]
            ],
            2 => [
                'Mon' => ['am'=>['E'], 'noon'=>['A', 'B'], 'pm'=>['F']],
                'Tue' => ['am'=>['C'], 'noon'=>['A', 'B'], 'pm'=>['D']],
                'Wed' => ['am'=>['E'], 'noon'=>['B', 'F'], 'pm'=>['A']],
                'Thu' => ['am'=>['C'], 'noon'=>['A', 'D'], 'pm'=>['E']],
                'Fri' => ['am'=>['F'], 'noon'=>['B', 'C'], 'pm'=>['D']],
                'W-e' => ['am'=>['E'], 'pm'=>['F']]
            ],
            3 => [
                'Mon' => ['am'=>['D'], 'noon'=>['A', 'B'], 'pm'=>['C']],
                'Tue' => ['am'=>['F'], 'noon'=>['A', 'B'], 'pm'=>['E']],
                'Wed' => ['am'=>['D'], 'noon'=>['B', 'C'], 'pm'=>['A']],
                'Thu' => ['am'=>['F'], 'noon'=>['A', 'E'], 'pm'=>['D']],
                'Fri' => ['am'=>['C'], 'noon'=>['B', 'F'], 'pm'=>['E']],
                'W-e' => ['am'=>['A'], 'pm'=>['B']]
            ],
            4 => [
                'Mon' => ['am'=>['D'], 'noon'=>['A', 'B'], 'pm'=>['C']],
                'Tue' => ['am'=>['F'], 'noon'=>['A', 'B'], 'pm'=>['E']],
                'Wed' => ['am'=>['D'], 'noon'=>['A', 'C'], 'pm'=>['B']],
                'Thu' => ['am'=>['F'], 'noon'=>['B', 'E'], 'pm'=>['D']],
                'Fri' => ['am'=>['C'], 'noon'=>['A', 'F'], 'pm'=>['E']],
                'W-e' => ['am'=>['D'], 'pm'=>['C']]
            ],
            5 => [
                'Mon' => ['am'=>['F'], 'noon'=>['A', 'B'], 'pm'=>['E']],
                'Tue' => ['am'=>['D'], 'noon'=>['A', 'B'], 'pm'=>['C']],
                'Wed' => ['am'=>['F'], 'noon'=>['B', 'E'], 'pm'=>['A']],
                'Thu' => ['am'=>['D'], 'noon'=>['A', 'C'], 'pm'=>['F']],
                'Fri' => ['am'=>['E'], 'noon'=>['B', 'D'], 'pm'=>['C']],
                'W-e' => ['am'=>['F'], 'pm'=>['E']]
            ]
        ];
        
        $this->arrPlanning = [];
        
        $urlElements = [self::CST_SUBONGLET => self::CST_LIB_COPS];
        
        $buttonContent = HtmlUtils::getLink('COPS', $this->getUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = [self::ATTR_CLASS=>($this->catSlug==''?$this->btnDisabled:$this->btnDark)];
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        
        $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
        if ($this->catSlug=='individual') {
            $id = $this->initVar(self::FIELD_ID);
            $attributes = [];
            $attributes[self::SQL_WHERE_FILTERS][self::FIELD_ID] = $id;
            $objsCops = $this->CopsPlayerServices->getCopsPlayers($attributes);
            $this->objCops = array_shift($objsCops);
            $name = $this->objCops->getField(self::FIELD_NOM).' '.$this->objCops->getField(self::FIELD_PRENOM);
            $urlElements[self::FIELD_ID] = $id;
            
            $buttonContent = HtmlUtils::getLink($name, $this->getUrl($urlElements), self::CST_TEXT_WHITE);
            $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
            $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        } elseif ($this->catSlug!='') {
            $name = $this->arrMenu[$this->catSlug];
            $buttonContent = HtmlUtils::getLink($name, $this->getUrl($urlElements), self::CST_TEXT_WHITE);
            $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
            $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, $buttonAttributes);
        } else {
            // TODO
        }
    }
    
    /**
     * @since 1.22.11.03
     * @version 1.22.11.03
     */
    public function getOngletContent()
    {
        switch ($this->catSlug) {
            case 'individual' :
                $returned = $this->getIndividual();
                break;
            case 'ranked' :
                $returned = $this->getListRanked();
                break;
            case 'alpha' :
            case 'beta' :
            case 'epsilon' :
                $returned = $this->getListTeam();
                break;
            case 'rotation' :
                $returned = $this->getRotationCops();
                break;
            case 'accueil' :
                $returned = $this->getRotationAccueil();
                break;
            default :
                // La page par défaut
                $urlTemplate = 'web/pages/public/fragments/public-fragments-article-cops-entete.php';
                $returned = $this->getContent($this->getRender($urlTemplate));
                break;
        }
        return $returned;
    }
    
    /**
     * @since v1.22.11.09
     * @version v1.23.05.28
     */
    public function getHeader(bool $blnRanked=true): string
    {
        // On va construire le Header du tableau
        $thAttributes = [self::ATTR_STYLE => 'width:50px'];
        $headerContent  = HtmlUtils::getTh('Masque', $thAttributes);
        $thAttributes = [self::ATTR_CLASS => 'mailbox-name'];
        $headerContent .= HtmlUtils::getTh('Matricule', $thAttributes);
        $headerContent .= HtmlUtils::getTh(self::LABEL_NOM, $thAttributes);
        $headerContent .= HtmlUtils::getTh('Surnom', $thAttributes);
        if ($blnRanked) {
            // Les gradés uniquement
            $headerContent .= HtmlUtils::getTh('Section', $thAttributes);
        }
        return $headerContent;
    }
    
    /**
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getMenuContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        // Pour ça, on doit récupérer les catégories Wp qui sont des enfants de la catégorie Index.
        $menuContent = '';
        $strClass = 'nav-link text-white';
        foreach ($this->arrMenu as $key => $value) {
            $aContent = '<i class="fa-solid fa-user-police-tie"></i>'.self::CST_NBSP.$value;
            $urlElements = [self::CST_SUBONGLET => self::CST_LIB_COPS, self::CST_CAT_SLUG => $key];
            $href = $this->getUrl($urlElements);
            $liContent = HtmlUtils::getLink($aContent, $href, $strClass);
            $strLiClass = 'nav-item'.($key==$this->catSlug ? ' '.self::CST_ACTIVE : '');
            $menuContent .= $this->getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>$strLiClass]);
        }
        /////////////////////////////////////////
        return $menuContent;
    }

    /**
     * @param string $mainContent
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getContent($mainContent)
    {
        $urlTemplate = self::WEB_PPFS_ONGLET;
        $attributes = [
            // L'id de la page
            'section-cops',
            // Le bouton éventuel de création / retour...
            '',
            // Le nom du bloc du menu de gauche
            'COPS',
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since v1.22.11.03
     * @version v1.22.11.09
     */
    public function getListRanked()
    {
        $attributes = [];
        $urlTemplateList = self::WEB_PPFS_ONGLET_LIST;
        $titre = 'Gradés';
        
        $strHeader = $this->getBalise(self::TAG_TR, $this->getHeader());
        /////////////////////////////////////////

        $listContent = '';
        $attributes[self::SQL_WHERE_FILTERS] = [
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH
        ];
        
        //////////////////////////////////////////////////////////
        // Gestion du Capitaine (potentiellement des Capitaines)
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Capitaine';
        $objCaptains = $this->CopsPlayerServices->getCopsPlayers($attributes);
        // Gestion des Lieutenants
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
        $objLieutenants = $this->CopsPlayerServices->getCopsPlayers($attributes);
        $objCopsPlayers = array_merge($objCaptains, $objLieutenants);
        $aAttributes = [self::CST_SUBONGLET => $this->slugSubOnglet, self::CST_CAT_SLUG => 'individual'];
        $href = $this->getUrl($aAttributes);

        while (!empty($objCopsPlayers)) {
            $objCopsPlayer = array_shift($objCopsPlayers);
            $section = $objCopsPlayer->getField(self::FIELD_SECTION);
            if (in_array($section, ['', 'A-Alpha', 'A-Beta', 'B-Epsilon'])) {
                // A terme, à supprimer
                $listContent .= $objCopsPlayer->getBean()->getLibraryRow($href);
            }
        }
        
        $listAttributes = [
            $titre,
            '',
            // Pas de pagination
            $strHeader,
            $listContent,
        ];
        /////////////////////////////////////////
        $mainContent = $this->getRender($urlTemplateList, $listAttributes);
        
        return $this->getContent($mainContent);
    }
    
    /**
     * @return string
     * @since v1.22.11.03
     * @version v1.22.11.09
     */
    public function getListTeam()
    {
        $attributes = [];
        $urlTemplateList = self::WEB_PPFS_ONGLET_LIST;
        $titre = 'A-Alpha / A-Beta / B-Epsilon';
        
        $strHeader = $this->getBalise(self::TAG_TR, $this->getHeader(false));
        /////////////////////////////////////////

        $listContent = '';
        $attributes[self::SQL_WHERE_FILTERS] = [
            self::FIELD_ID => self::SQL_JOKER_SEARCH,
            self::FIELD_MATRICULE => self::SQL_JOKER_SEARCH,
            self::FIELD_PASSWORD => self::SQL_JOKER_SEARCH
        ];
        
        //////////////////////////////////////////////////////////
        // Gestion du Lieutenant
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Lieutenant';
        $objLieutenants = $this->CopsPlayerServices->getCopsPlayers($attributes);
        // Gestion des Détectives
        $tsToday = DateUtils::getCopsDate(self::FORMAT_DATE_YMD);
        $attributes[self::SQL_WHERE_FILTERS][self::FIELD_GRADE] = 'Détective';
        $attributes[self::SQL_ORDER_BY] = self::FIELD_NOM;
        $objDetectives = $this->CopsPlayerServices->getCopsPlayers($attributes);
        $objCopsPlayers = array_merge($objLieutenants, $objDetectives);
        $aAttributes = [self::CST_SUBONGLET => $this->slugSubOnglet, self::CST_CAT_SLUG => 'individual'];
        $href = $this->getUrl($aAttributes);
        
        while (!empty($objCopsPlayers)) {
            $objCopsPlayer = array_shift($objCopsPlayers);
            $section = $objCopsPlayer->getField(self::FIELD_SECTION);
            if ($section!=$this->arrMenu[$this->catSlug]) {
                // Si le Cops n'est pas dans la bonne section (ce serait bien de filtrer selon ce critère)
                continue;
            }
            if ($objCopsPlayer->getField(self::FIELD_INTEGRATION_DATE) <= $tsToday) {
                $listContent .= $objCopsPlayer->getBean()->getLibraryRow($href, false);
            }
        }

        $listAttributes = [
            $titre,
            '',
            // Pas de pagination
            $strHeader,
            $listContent,
        ];
        /////////////////////////////////////////
        $mainContent = $this->getRender($urlTemplateList, $listAttributes);
        
        return $this->getContent($mainContent);
    }

    /**
     * @return string
     * @since v1.22.11.09
     * @version v1.23.05.28
     */
    public function getRotationCops()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-rotation-cops.php';
        
        // Si la date n'est pas définie, on récupère celle du jour.
        // Sinon, on récupère la date passée en paramètre.
        $strParam = (string) $this->initVar('strDate', DateUtils::getCopsDate(self::FORMAT_DATE_DMY));
        [$dDate, $mDate, $yDate] = explode('-', $strParam);
        $tsNow = mktime(0, 0, 0, $mDate, $dDate, $yDate);
        
        // Si la date en question n'est pas un lundi, on récupère le lundi précédent.
        $nDay = date('N', $tsNow);
        $dAjust = ($nDay!=1 ? $nDay-1 : 0);

        // Header - 4 semaines concernées
        $strHeader = '';
        for ($i=0; $i<4; ++$i) {
            $strDate = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate+$dAjust+$i*7, $yDate));
            $href = $this->getUrl([self::CST_CAT_SLUG=>'rotation', 'strDate'=>$strDate]);
            $divContent = HtmlUtils::getLink($strDate, $href, self::CST_TEXT_WHITE);
            $strHeader .= HtmlUtils::getDiv($divContent, [self::ATTR_CLASS=>'col-3 table-bordered']);
        }

        // Bouton précédent
        $prevDate = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate+$dAjust-7, $yDate));
        $aContent = HtmlUtils::getIcon(self::I_CARET_LEFT);
        $href = $this->getUrl([self::CST_CAT_SLUG=>'rotation', 'strDate'=>$prevDate]);
        $btnContent = HtmlUtils::getLink($aContent, $href, self::CST_TEXT_WHITE);
        $prevBtn  = HtmlUtils::getButton($btnContent, [self::ATTR_CLASS=>self::CST_TEXT_WHITE]);
        $prevBtn .= self::CST_NBSP.$prevDate;
        
        // Bouton suivant
        $nextDate = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate+$dAjust+7, $yDate));
        $aContent = HtmlUtils::getIcon(self::I_CARET_RIGHT);
        $href = $this->getUrl([self::CST_CAT_SLUG=>'rotation', 'strDate'=>$nextDate]);
        $btnContent = HtmlUtils::getLink($aContent, $href, self::CST_TEXT_WHITE);
        $nextBtn  = $nextDate.self::CST_NBSP;
        $nextBtn .= HtmlUtils::getButton($btnContent, [self::ATTR_CLASS=>self::CST_TEXT_WHITE]);
        
        // Décalage de semaines
        $tsStart = mktime(0, 0, 0, 6, 3, 2030);
        $nbDiffWeeks = (($tsNow-$tsStart)/60/60/24/7)%4;
        if ($nbDiffWeeks<0) {
            $nbDiffWeeks = 4+$nbDiffWeeks;
        }
        
        // Roulement des équipes
        $arrSections = [
            'A' => ['Alpha', 'Beta', 'Gamma', self::CST_NBSP],
            'B' => ['Epsilon', 'Theta', 'Mu', 'Delta'],
            'C' => ['Sigma', 'Tau', self::CST_NBSP, 'Pi']
        ];
        for ($i=0; $i<$nbDiffWeeks; ++$i) {
            $strSection = array_pop($arrSections['A']);
            array_unshift($arrSections['A'], $strSection);
            $strSection = array_pop($arrSections['B']);
            array_unshift($arrSections['B'], $strSection);
            $strSection = array_pop($arrSections['C']);
            array_unshift($arrSections['C'], $strSection);
        }
        
        $divAttributes = [self::ATTR_CLASS=>'col-1 table-bordered'];
        $strRowA = '';
        $strRowB = '';
        $strRowC = '';
        for ($i=0; $i<12; ++$i) {
            $strRowA .= HtmlUtils::getDiv($arrSections['A'][$i%4], $divAttributes);
            $strRowB .= HtmlUtils::getDiv($arrSections['B'][$i%4], $divAttributes);
            $strRowC .= HtmlUtils::getDiv($arrSections['C'][$i%4], $divAttributes);
        }
        
        $attributes = [$strHeader, $prevBtn, $nextBtn, $strRowA, $strRowB, $strRowC];
        $mainContent = $this->getRender($urlTemplate, $attributes);
        
        return $this->getContent($mainContent);
    }

    /**
     * @return string
     * @since v1.22.11.09
     * @version v1.23.05.28
     */
    public function getRotationAccueil()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-rotation-accueil.php';
        
        // Si la date n'est pas définie, on récupère celle du jour.
        // Sinon, on récupère la date passée en paramètre.
        $strParam = (string) $this->initVar('strDate', DateUtils::getCopsDate(self::FORMAT_DATE_DMY));
        [$dDate, $mDate, $yDate] = explode('-', $strParam);
        $tsNow = mktime(0, 0, 0, $mDate, $dDate, $yDate);
        
        // Bandeau de la semaine
        $strBandeau  = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate, $yDate)).' au ';
        $strBandeau .= date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate+6, $yDate));

        // Bouton précédent
        $prevDate = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate-7, $yDate));
        $aContent = HtmlUtils::getIcon(self::I_CARET_LEFT);
        $href = $this->getUrl([self::CST_CAT_SLUG=>'accueil', 'strDate'=>$prevDate]);
        $btnContent = HtmlUtils::getLink($aContent, $href, self::CST_TEXT_WHITE);
        $prevBtn  = HtmlUtils::getButton($btnContent, [self::ATTR_CLASS=>self::CST_TEXT_WHITE]);
        $prevBtn .= self::CST_NBSP.$prevDate;

        // Bouton suivant
        $nextDate = date(self::FORMAT_DATE_DMY, mktime(0, 0, 0, $mDate, $dDate+7, $yDate));
        $aContent = HtmlUtils::getIcon(self::I_CARET_RIGHT);
        $href = $this->getUrl([self::CST_CAT_SLUG=>'accueil', 'strDate'=>$nextDate]);
        $btnContent = HtmlUtils::getLink($aContent, $href, self::CST_TEXT_WHITE);
        $nextBtn  = $nextDate.self::CST_NBSP;
        $nextBtn .= HtmlUtils::getButton($btnContent, [self::ATTR_CLASS=>self::CST_TEXT_WHITE]);
                
        ///////////////////////////////////////////////////////
        // Gestion des Offiers de Police
        // Décalage de semaines
        $tsStart = mktime(0, 0, 0, 6, 3, 2030);
        $intModulo = is_countable($this->arrRotationsOP) ? count($this->arrRotationsOP) : 0;
        $nbDiffWeeks = (($tsNow-$tsStart)/60/60/24/7)%$intModulo;
        if ($nbDiffWeeks<0) {
            $nbDiffWeeks = (is_countable($this->arrRotationsOP) ? count($this->arrRotationsOP) : 0)+$nbDiffWeeks;
        }
        foreach ($this->arrRotationsOP[$nbDiffWeeks] as $key => $arrRotationOP) {
            $this->buildDayColumnContentOP($key, $arrRotationOP);
        }
        ///////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////
        // Gestion des Auxiliaires Civils
        // Décalage de semaines
        $tsStart = mktime(0, 0, 0, 6, 3, 2030);
        $nbDiffDays = (($tsNow-$tsStart)/60/60/24)%7;
        if ($nbDiffDays<0) {
            $nbDiffDays = 7+$nbDiffDays;
        }
        for ($i=0; $i<$nbDiffDays; ++$i) {
            $strAuxCiv = array_pop($this->arrAuxiliairesCivils);
            array_unshift($this->arrAuxiliairesCivils, $strAuxCiv);
        }
        foreach ($this->arrRotationsAC as $key => $arrRotationAC) {
            $this->buildDayColumnContentAC($key, $arrRotationAC);
        }
        ///////////////////////////////////////////////////////
        
        $attributes = [$strBandeau, $prevBtn, $nextBtn, $this->buildWeekGrid()];
        $mainContent = $this->getRender($urlTemplate, $attributes);
        
        return $this->getContent($mainContent);
    }

    /**
     * @param string $key
     * @param string $quand
     * @param string $strClass
     * @param string $strStyle
     * @param string $strHoraire
     * @since v1.22.11.09
     * @version v1.22.11.09
     */
    public function defineExtras($key, $quand, &$strClass, &$strStyle, &$strHoraire)
    {
        if ($key=='W-e') {
            $strStyle = 'height: '.($this->lineHeight*13).'px;';
            if ($quand=='pm') {
                $strClass = ' bg-primary offset-6';
                $strStyle .= 'margin-top: -'.$this->lineHeight.'px;';
                $strHoraire = '12h-01h';
            } else {
                $strClass = ' bg-pink';
                $strHoraire = '00h-13h';
            }
        } else {
            $strStyle = 'height: '.($this->lineHeight*9).'px;';
            if ($quand=='am') {
                $strClass = ' bg-yellow';
                $strHoraire = '00h-09h';
            } else {
                $strStyle .= 'margin-top: -'.$this->lineHeight.'px;';
                if ($quand=='pm') {
                    $strClass = ' bg-danger';
                    $strHoraire = '16h-01h';
                } else {
                    $strClass = ' bg-info offset-6';
                    $strHoraire = '08h-17h';
                }
            }
        }
    }
    
    /**
     * @return string
     * @since v1.22.11.09
     * @version v1.23.05.28
     */
    public function buildWeekGrid(): string
    {
        $ulAttributes = [self::ATTR_CLASS => 'p-0', self::ATTR_STYLE => 'list-style-type: none;'];
        $strContent = '';
        foreach ($this->arrPlanning as $key => $arrDay) {
            $divDayContent = '';
            foreach ($arrDay as $quand => $str) {
                $strClass = '';
                $strStyle = '';
                $strHoraire = '';
                $this->defineExtras($key, $quand, $strClass, $strStyle, $strHoraire);
                
                $divCreneauContent = HtmlUtils::getDiv($strHoraire, [self::ATTR_CLASS=>'bg-dark me-1 ms-1']);
                $divCreneauContent .= $this->getBalise(self::TAG_UL, $str, $ulAttributes);
                
                $divAttributes = [self::ATTR_CLASS => 'col-6 p-0'.$strClass, self::ATTR_STYLE => $strStyle];
                $divDayContent .= HtmlUtils::getDiv($divCreneauContent, $divAttributes);
            }
            $strContent .= HtmlUtils::getDiv($divDayContent, [self::ATTR_CLASS=>'col-2 table-bordered']);
        }
        return $strContent;
    }

    /**
     * @param string $key
     * @param array $arrRotationAC
     * @since v1.22.11.09
     * @version v1.22.11.09
     */
    public function buildDayColumnContentAC($key, $arrRotationAC)
    {
        while (!empty($arrRotationAC[0])) {
            $index = array_shift($arrRotationAC[0]);
            [$nom] = explode(' ', (string) $this->arrAuxiliairesCivils[$index]);
            $this->arrPlanning[$key]['am'] .= $this->getLiACOP($nom);
        }

        while (!empty($arrRotationAC[1])) {
            $index = array_shift($arrRotationAC[1]);
            [$nom] = explode(' ', (string) $this->arrAuxiliairesCivils[$index]);
            $this->arrPlanning[$key][($key!='W-e' ? 'noon' : 'pm')] .= $this->getLiACOP($nom);
        }
        
        while (!empty($arrRotationAC[2])) {
            $index = array_shift($arrRotationAC[2]);
            [$nom] = explode(' ', (string) $this->arrAuxiliairesCivils[$index]);
            $this->arrPlanning[$key]['pm'] .= $this->getLiACOP($nom);
        }
    }
    
    /**
     * @param string $nom
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getLiACOP($nom)
    { return '<li style="line-height:'.$this->lineHeight.'px;">'.$nom.'</li>'; }

    /**
     * @param string $key
     * @param array $arrRotationOP
     * @since v1.22.11.10
     * @version v1.22.11.10
     */
    public function buildDayColumnContentOP($key, $arrRotationOP)
    {
        if (!isset($this->arrPlanning[$key])) {
            $this->arrPlanning[$key]['am'] = '';
            if ($key!='W-e') {
                $this->arrPlanning[$key]['noon'] = '';
            }
            $this->arrPlanning[$key]['pm'] = '';
        }

        while (!empty($arrRotationOP['am'])) {
            $index = array_shift($arrRotationOP['am']);
            [$nom] = explode(' ', (string) $this->arrOfficiersPolice[$index]);
            $this->arrPlanning[$key]['am'] .= $this->getLiACOP($nom);
        }

        while (!empty($arrRotationOP['noon'])) {
            $index = array_shift($arrRotationOP['noon']);
            [$nom] = explode(' ', (string) $this->arrOfficiersPolice[$index]);
            $this->arrPlanning[$key]['noon'] .= $this->getLiACOP($nom);
        }
        
        while (!empty($arrRotationOP['pm'])) {
            $index = array_shift($arrRotationOP['pm']);
            [$nom] = explode(' ', (string) $this->arrOfficiersPolice[$index]);
            $this->arrPlanning[$key]['pm'] .= $this->getLiACOP($nom);
        }
    }

    /**
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getIndividual()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-article-cops-individual.php';
        $attributes = [
            $this->objCops->getField(self::FIELD_NOM).' '.$this->objCops->getField(self::FIELD_PRENOM),
            $this->objCops->getField(self::FIELD_SURNOM),
            $this->objCops->getField(self::FIELD_MATRICULE),
            $this->objCops->getField(self::FIELD_BIRTH_DATE),
            $this->objCops->getField(self::FIELD_TAILLE)/100,
            $this->objCops->getField(self::FIELD_POIDS),
            '',
            '',
            '',
            ''
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
}
