<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsFilePageBean
 * @author Hugues
 * @since 1.22.09.20
 * @version 1.22.09.20
 */
class AdminCopsFilePageBean extends AdminCopsPageBean implements ConstantsInterface
{
  public function __construct()
  {
    parent::__construct();

    /////////////////////////////////////////
    // Construction du menu de l'inbox
    $this->arrSubOnglets = array(
      self::CST_FILE_OPENED  => array(self::FIELD_ICON => 'file',         		self::FIELD_LABEL => 'En cours'),
      self::CST_FILE_CLOSED  => array(self::FIELD_ICON => 'file-circle-check',  self::FIELD_LABEL => 'Classées'),
      self::CST_FILE_COLDED  => array(self::FIELD_ICON => 'file-circle-minus',  self::FIELD_LABEL => 'Cold Case'),
      self::CST_FOLDER_READ   => array(self::FIELD_LABEL => 'Lire'),
      self::CST_FOLDER_WRITE  => array(self::FIELD_LABEL => 'Rédiger'),
    );
    /////////////////////////////////////////
	$this->urlOnglet = '/admin?onglet=dossier';
	$this->url = '/admin?onglet=dossier&subOnglet=';

    /////////////////////////////////////////
    // Définition des services
	$this->CopsEnqueteServices = new CopsEnqueteServices();

  }
  /**
   * @return string
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
  public function getBoard()
  {
    $this->subOnglet = (isset($this->urlParams[self::CST_SUBONGLET]) && isset($this->arrSubOnglets[$this->urlParams[self::CST_SUBONGLET]]) ? $this->urlParams[self::CST_SUBONGLET] : self::CST_FILE_OPENED);
    $label = $this->arrSubOnglets[$this->subOnglet][self::FIELD_LABEL];

    $this->buildBreadCrumbs('Enquêtes', self::ONGLET_FILE, true);
	
	////////////////////////////////////////////////////////
	// Insertion / Mise à jour de l'enquête saisie.
    if (isset($this->urlParams['writeAction'])) {
		if ($this->urlParams[self::FIELD_NOM_ENQUETE]!='') {
			$this->updateEnquete();
		}
    }	
	////////////////////////////////////////////////////////

    $urlTemplate = 'web/pages/public/public-board.php';
    $attributes = array(
      // La sidebar
      $this->getSideBar(),
      // Le contenu de la page
      $this->getOngletContent(),
      // L'id
      $this->CopsPlayer->getMaskMatricule(),
      // Le nom
      $this->CopsPlayer->getFullName(),
      // La barre de navigation
      $this->getNavigationBar(),
      // Le content header
      $this->getContentHeader(),
      '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
  public function getOngletContent()
  {

    /////////////////////////////////////////
    // Construction du panneau de droite
    switch ($this->subOnglet) {
	/*
      case self::CST_FOLDER_READ :
        $strRightPanel = $this->getReadMessageBlock();
        $strButtonRetour = '<a href="/admin?onglet=inbox&subOnglet='.$this->Folder->getField(self::FIELD_SLUG).'" class="btn btn-primary btn-block mb-3"><i class="fa-solid fa-backward"></i> '.$this->Folder->getField(self::FIELD_LABEL).'</a>';
      break;
	*/
      case self::CST_FOLDER_WRITE :
        $strRightPanel = $this->getWriteMessageBlock();
        $strButtonRetour = '<a href="'.$this->urlOnglet.'" class="btn btn-primary btn-block mb-3"><i class="fa-solid fa-backward"></i> Retour</a>';
      break;
      default :
        $strRightPanel = $this->getFolderEnquetesList();
        $strButtonRetour = '<a href="'.$this->url.'write" class="btn btn-primary btn-block mb-3">Ouvrir une enquête</a>';
      break;
    }
    /////////////////////////////////////////

    $strLeftPanel = $this->getFolderBlock();

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquetes.php';
    $attributes = array(
      // Contenu du panneau latéral gauche
      $strLeftPanel,
      // Contenu du panneau principal
      $strRightPanel,
      // Eventuel bouton de retour si on est en train de lire ou rédiger un message
      $strButtonRetour,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
  public function getFolderBlock()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-li-menu-folder.php';
    /////////////////////////////////////////
    // Construction du panneau de gauche
    $strLeftPanel = '';
	foreach ($this->arrSubOnglets as $slug => $subOnglet) {
		if (!isset($subOnglet[self::FIELD_ICON])) {
			continue;
		}
		$attributes = array(
		  // Menu sélectionné ou pas ?
		  ($slug==$this->subOnglet ? ' '.self::CST_ACTIVE : ''),
		  // L'url du folder
		  $this->url.$slug,
		  // L'icône
		  $subOnglet[self::FIELD_ICON],
		  // Le libellé + l'éventuel badge
		  $subOnglet[self::FIELD_LABEL],
		);
		$strLeftPanel .= $this->getRender($urlTemplate, $attributes);
	}
    /////////////////////////////////////////
    return $strLeftPanel;
  }
  
  /**
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
  public function getFolderEnquetesList()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-file-enquetes.php';
    /////////////////////////////////////////
    // Construction du panneau de droite
	// Liste des dossiers pour une catégorie spécifique (En cours, Classées, Cold Case...)
	switch ($this->subOnglet) {
		case self::CST_FILE_CLOSED :
			$attributes = array(self::SQL_WHERE_FILTERS => array(
				self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_CLOSED,
			));
		break;
		case self::CST_FILE_COLDED :
			$attributes = array(self::SQL_WHERE_FILTERS => array(
				self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_CASECLOSE,
			));
		break;
		case self::CST_FILE_OPENED :
		default :
			$attributes = array(self::SQL_WHERE_FILTERS => array(
				self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_OPENED,
			));
		break;
	}
	
	$CopsEnquetes = $this->CopsEnqueteServices->getEnquetes($attributes);
	if (empty($CopsEnquetes)) {
		$strContent = '<tr><td class="text-center">Aucune enquête.<br></td></tr>';
	} else {
		foreach ($CopsEnquetes as $CopsEnquete) {
			$strContent .= $CopsEnquete->getBean()->getCopsEnqueteRow();
		}
	}
    /////////////////////////////////////////
	// Gestion de la pagination
	$strPagination = '';
    /////////////////////////////////////////
	
	
    $attributes = array(
      // Titre du dossier affiché
      $this->arrSubOnglets[$this->subOnglet][self::FIELD_LABEL],
      // Nombre de messages dans le dossier affiché : 1-50/200
      $strPagination,
      // La liste des messages du dossier affiché
      $strContent,
      // Le slug du dossier affiché
      $this->url.$this->subOnglet,
    );
    /////////////////////////////////////////
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.09.20
   * @version 1.22.09.20
   */
  public function getWriteMessageBlock()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-file-write.php';
    /////////////////////////////////////////
    // Construction du panneau de droite
	// Gestion d'édition (création ou modification) d'un dossier d'enquête

    $attributes = array(
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
    );
    /////////////////////////////////////////
    return $this->getRender($urlTemplate, $attributes);
  }

	public function updateEnquete()
	{
		
      $attributes = array(
        // Le nom de l'enquête
        self::FIELD_NOM_ENQUETE    		=> $this->urlParams[self::FIELD_NOM_ENQUETE],
        // L'id du premier enquêteur
        self::FIELD_IDX_ENQUETEUR		=> $this->urlParams[self::FIELD_IDX_ENQUETEUR],
        // L'id du District Attorney
        self::FIELD_IDX_DISTRICT_ATT    => $this->urlParams[self::FIELD_IDX_DISTRICT_ATT],
        // Le résyumé des faits
        self::FIELD_RESUME_FAITS 		=> $this->urlParams[self::FIELD_RESUME_FAITS],
        // La description de la scène de crime
        self::FIELD_DESC_SCENE_CRIME    => $this->urlParams[self::FIELD_DESC_SCENE_CRIME],
        // Les pistes et les démarches
        self::FIELD_PISTES_DEMARCHES    => $this->urlParams[self::FIELD_PISTES_DEMARCHES],
        // Les notes diverses
        self::FIELD_NOTES_DIVERSES    	=> $this->urlParams[self::FIELD_NOTES_DIVERSES],
        // La date de dernière modification
        self::FIELD_DLAST    			=>  time(),
      );

      if ($this->urlParams[self::FIELD_ID]!='') {
        $attributes[self::FIELD_ID] = $this->urlParams[self::FIELD_ID];
        $CopsEnquete = new CopsEnquete($attributes);
        $this->CopsEnqueteServices->updateEnquete($CopsEnquete);
      } else {
        $attributes[self::FIELD_DSTART] = time();
        $attributes[self::FIELD_STATUT_ENQUETE] = self::CST_ENQUETE_OPENED;
        $CopsEnquete = new CopsEnquete($attributes);
        $this->CopsEnqueteServices->insertEnquete($CopsEnquete);
	}
}
}
