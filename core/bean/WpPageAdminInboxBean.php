<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageAdminInboxBean
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.05.10
 */
class WpPageAdminInboxBean extends WpPageAdminBean
{
  public function __construct()
  {
    parent::__construct();

    /////////////////////////////////////////
    // Construction du menu de l'inbox
    $this->arrSubOnglets = array(
      self::CST_FOLDER_INBOX  => array(self::FIELD_ICON => 'inbox',         self::FIELD_LABEL => 'Réception'),
      self::CST_FOLDER_DRAFT  => array(self::FIELD_ICON => 'file-lines',    self::FIELD_LABEL => 'Brouillons'),
      self::CST_FOLDER_SENT   => array(self::FIELD_ICON => 'paper-plane',   self::FIELD_LABEL => 'Envoyés'),
      self::CST_FOLDER_EVENTS => array(self::FIELD_ICON => 'calendar-days', self::FIELD_LABEL => 'Événements'),
      self::CST_FOLDER_ALERT  => array(self::FIELD_ICON => 'bell',          self::FIELD_LABEL => 'Notifications'),
      self::CST_FOLDER_TRASH  => array(self::FIELD_ICON => 'trash',         self::FIELD_LABEL => 'Corbeille'),
      self::CST_FOLDER_SPAM   => array(self::FIELD_ICON => 'filter',        self::FIELD_LABEL => 'Spam'),
      self::CST_FOLDER_READ   => array(self::FIELD_LABEL => 'Lire'),
      self::CST_FOLDER_WRITE  => array(self::FIELD_LABEL => 'Rédiger'),
    );
    /////////////////////////////////////////

    /////////////////////////////////////////
    // Définition des services
    $this->CopsMailServices = new CopsMailServices();

  }
  /**
   * @return string
   * @since 1.22.04.29
   * @version 1.22.06.09
   */
  public function getBoard()
  {
    $this->subOnglet = (isset($this->urlParams[self::CST_SUBONGLET]) && isset($this->arrSubOnglets[$this->urlParams[self::CST_SUBONGLET]]) ? $this->urlParams[self::CST_SUBONGLET] : self::CST_FOLDER_INBOX);
    $label = $this->arrSubOnglets[$this->subOnglet][self::FIELD_LABEL];

    // On devrait ici contrôler les actions et les effectuer
    // read ? -> On marque le message lu
    if ($this->subOnglet==self::CST_FOLDER_READ) {
      $this->CopsMailJoint = $this->CopsMailServices->getMailJoint($this->urlParams[self::FIELD_ID]);
      $this->CopsMailJoint->setField(self::FIELD_LU, 1);
      $this->CopsMailServices->updateMailJoint($this->CopsMailJoint);

      $MailFolder = $this->CopsMailJoint->getMailFolder();
      $label = $MailFolder->getField(self::FIELD_LABEL);
    } elseif ($this->subOnglet==self::CST_FOLDER_WRITE) {
      if (isset($this->urlParams[self::FIELD_ID])) {
        $this->CopsMailJoint = $this->CopsMailServices->getMailJoint($this->urlParams[self::FIELD_ID]);
      } else {
        $this->CopsMailJoint = new CopsMailJoint();
      }
      $MailFolder = $this->CopsMailJoint->getMailFolder();
      $label = $MailFolder->getField(self::FIELD_LABEL);
    }

    // trash ? -> On supprimer les messages
    if (isset($this->urlParams[self::CST_FOLDER_TRASH])) {
      $ids = explode(',', $this->urlParams['ids']);
      // On parcourt la liste des ids
      while (!empty($ids)) {
        $id = array_shift($ids);
        $CopsMailJoint = $this->CopsMailServices->getMailJoint($id);
        // On vérifie que l'id est existant, qu'il appartient bien au folder actuel et que le user actuel en est bien le destinataire
        if ($CopsMailJoint->getField(self::FIELD_TO_ID)==2) {
          if ($this->subOnglet!=self::CST_FOLDER_TRASH) {
            // Si les contrôles sont okay, on déplace le message vers le folder trash.
            $CopsMailJoint->setField(self::FIELD_FOLDER_ID, 6);
          } else {
            // Si le folder actuel est trash, on le supprime définitivement. (bon en fait, on le met dans le folder 10 qui n'existe pas)
            $CopsMailJoint->setField(self::FIELD_FOLDER_ID, 10);
          }
          $this->CopsMailServices->updateMailJoint($CopsMailJoint);
        }
      }
    }

    if (isset($this->urlParams['writeAction'])) {
      // L'action souhaitée parmi draft (on va sauvegarder en brouillon), send (on va envoyer)
      $mailAction = $this->urlParams['writeAction'];
      $attributes = array(
        // Le sujet
        self::FIELD_MAIL_SUBJECT    => $this->urlParams[self::FIELD_MAIL_SUBJECT],
        // Le contenu
        self::FIELD_MAIL_CONTENT    => $this->urlParams[self::FIELD_MAIL_CONTENT],
        // Date d'envoi
        self::FIELD_MAIL_DATE_ENVOI => ($mailAction==self::CST_FOLDER_DRAFT ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s')),
      );

      if ($this->urlParams[self::FIELD_ID]!='') {
        $attributes[self::FIELD_ID] = $this->urlParams[self::FIELD_ID];
        $CopsMail = new CopsMail($attributes);
        $this->CopsMailServices->updateMail($CopsMail);
      } else {
        // On insère le nouveau mail dans cops_mail.
        // Toutefois, si c'est un draft, la dateEnvoi est à 0000-00-00 00:00:00
        $CopsMail = new CopsMail($attributes);
        $this->CopsMailServices->insertMail($CopsMail);
      }

      $CopsMailUsers = $this->CopsMailServices->getMailUsers(array(self::FIELD_MAIL=>$this->urlParams['mailTo']));
      $CopsMailUser = array_shift($CopsMailUsers);

      $attributes = array(
        self::FIELD_MAIL_ID     => $CopsMail->getField(self::FIELD_ID),
        self::FIELD_TO_ID       => $CopsMailUser->getField(self::FIELD_ID),
        self::FIELD_FROM_ID     => $this->urlParams['mailFrom'],
        self::FIELD_NB_PJS      => 0,
      );
      // TODO : Ici, on doit faire des updates si on est dans le cas d'une modification d'un drf
      // Pour mémoire, c'était un draft si $this->urlParams['id']!=''
      // Puis, on insère dans cops_mail_joint dans le folder sent ou draft
      // Si ce n'est pas un draft, on l'insère aussi dans inbox
      if ($mailAction==self::CST_FOLDER_DRAFT) {
        $attributes[self::FIELD_FOLDER_ID] = 2;
        $attributes[self::FIELD_LU] = 1;
        $CopsMailJoint = new CopsMailJoint($attributes);
        $this->CopsMailServices->insertMailJoint($CopsMailJoint);
      } else {
        // Pour le destinataire (unique pour le moment)
        $attributes[self::FIELD_FOLDER_ID] = 1;
        $attributes[self::FIELD_LU] = 0;
        $CopsMailJoint = new CopsMailJoint($attributes);
        $this->CopsMailServices->insertMailJoint($CopsMailJoint);
        // Pour l'expéditeur
        $attributes[self::FIELD_FOLDER_ID] = 3;
        $attributes[self::FIELD_LU] = 1;
        $CopsMailJoint = new CopsMailJoint($attributes);
        $this->CopsMailServices->insertMailJoint($CopsMailJoint);
      }
    }

    $this->buildBreadCrumbs('Messagerie', self::ONGLET_INBOX, true);

    // Soit on est loggué et on affiche le contenu du bureau du cops
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
   * @since 1.22.04.29
   * @version 1.22.05.10
   */
  public function getOngletContent()
  {
    // Définir message précédent
    // Définir message suivant

    /////////////////////////////////////////
    // COnstruction du panneau de droite
    // On le fait avant le panneau de gauche, pour le cas où on afficherait un message non lu
    // qui deviendrait lu et donc ne doit plus remonter comme tel dans les badges
    switch ($this->subOnglet) {
      case self::CST_FOLDER_READ :
        $strRightPanel = $this->getReadMessageBlock();
        $strButtonRetour = '<a href="/admin?onglet=inbox&subOnglet='.$this->Folder->getField(self::FIELD_SLUG).'" class="btn btn-primary btn-block mb-3"><i class="fa-solid fa-backward"></i> '.$this->Folder->getField(self::FIELD_LABEL).'</a>';
      break;
      case self::CST_FOLDER_WRITE :
        $strRightPanel = $this->getWriteMessageBlock();
        $strButtonRetour = '<a href="/admin?onglet=inbox" class="btn btn-primary btn-block mb-3"><i class="fa-solid fa-backward"></i> Retour</a>';
      break;
      default :
        $strRightPanel = $this->getFolderMessagesList();
        $strButtonRetour = '<a href="/admin?onglet=inbox&subOnglet=write" class="btn btn-primary btn-block mb-3">Rédiger un message</a>';
      break;
    }
    /////////////////////////////////////////

    $strLeftPanel = $this->getFolderBlock();

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox.php';
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
   * @since 1.22.05.06
   * @version 1.22.05.06
   */
  public function getReadMessageBlock()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-read.php';

    $CopsMail = $this->CopsMailJoint->getMail();
    $this->Folder   = $this->CopsMailJoint->getMailFolder();

    //////////////////////////////////////////////////
    // Construction des liens pour accéder au message suivant/précédent
    // ou retour au dossier contenant le message courant
    $PrevMailJoint = $this->CopsMailServices->getPrevNextMailJoint($this->CopsMailJoint);
    $prevId = $PrevMailJoint->getField(self::FIELD_ID);
    if ($prevId=='') {
      $url = '/admin?onglet=inbox&subOnglet='.$this->CopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG);
    } else {
      $url = '/admin?onglet=inbox&subOnglet=read&id='.$prevId;
    }
    $cardTools  = '<button class="btn btn-dark btn-sm"><a href="'.$url.'" class="btn btn-tool" title="Précédent"><i class="fa-solid fa-chevron-left"></i></a></button>';
    $NextMailJoint = $this->CopsMailServices->getPrevNextMailJoint($this->CopsMailJoint, false);
    $nextId = $NextMailJoint->getField(self::FIELD_ID);
    if ($nextId=='') {
      $url = '/admin?onglet=inbox&subOnglet='.$this->CopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG);
    } else {
      $url = '/admin?onglet=inbox&subOnglet=read&id='.$nextId;
    }
    $cardTools .= '<button class="btn btn-dark btn-sm"><a href="'.$url.'" class="btn btn-tool" title="Suivant"><i class="fa-solid fa-chevron-right"></i></a></button>';
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // Construction de la chaine de réception
    $strAdress = 'De : '.$this->CopsMailJoint->getAuteur()->getField(self::FIELD_MAIL); // 'From: tintin@herge.fr<br>Cc: haddock@herge.fr'
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // Construction de la date d'envoi
    $strDateEnvoi = $CopsMail->getDateEnvoiFormate();

    $attributes = array(
      // Titre du mail
      $CopsMail->getField(self::FIELD_MAIL_SUBJECT),
      // From
      $strAdress,
      // Date envoi
      $strDateEnvoi,//'15 Feb. 2015 11:03 PM',
      // Corps du message
      $CopsMail->getField(self::FIELD_MAIL_CONTENT),
      // Liste des pièces jointes
      '',
      // les Card Tools
      $cardTools,
      // Le lien suppression
      '/admin?onglet=inbox&subOnglet='.$this->Folder->getField(self::FIELD_SLUG).'&trash&id='.$this->CopsMailJoint->getField(self::FIELD_ID),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getWriteMessageBlock()
  {
    $CopsMail = $this->CopsMailJoint->getMail();
    $Folder   = $this->CopsMailJoint->getMailFolder();
    $CopsMailUser = $this->CopsMailServices->getMailUser($this->CopsMailJoint->getField(self::FIELD_TO_ID));

    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-write.php';
    $attributes = array(
      // La liste des expéditeurs, visible ou non, si on est admin (liste d'options)
      ' disabled',
      // La liste des expéditeurs, son contenu, si on est admin (liste d'options)
      $this->getExpediteurOptions(),
      // Les destinataires par défaut (dans le cas d'un reply par exemple)
      $CopsMailUser->getField(self::FIELD_MAIL),
      // Le sujet par défaut (dans le cas d'un reply ou d'un share)
      $CopsMail->getField(self::FIELD_MAIL_SUBJECT),
      // Le message par défaut (dans le textarea, dans le cas d'un reply ou d'un share)
      $CopsMail->getField(self::FIELD_MAIL_CONTENT),
      // Le message par défaut stylisée dans le champ de saisie dans le cas d'un reply ou d'un share)
      $CopsMail->getField(self::FIELD_MAIL_CONTENT),
      // L'id éventuel du mail (dans le cas de la rédaction d'un draft)
      $CopsMail->getField(self::FIELD_ID),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  public function getExpediteurOptions()
  {
    $strExpediteurOptions = '';

    $fromId = $this->CopsMailJoint->getField(self::FIELD_FROM_ID);
    if ($fromId=='') {
      $CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
      $fromId = $CopsPlayer->getField(self::FIELD_ID);
    }

    $strExpediteurOptions .= '<option value="1"'.($fromId==1 ? ' selected' : '').'>Ressources Humaines</option>';
    $strExpediteurOptions .= '<option value="2"'.($fromId==2 ? ' selected' : '').'>COPS 101</option>';

    return $strExpediteurOptions;
  }

  /**
   * @since 1.22.05.02
   * @version 1.22.05.04
   */
  public function getFolderMessagesList()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-messages.php';

    ///////////////////////////////////////////////////////////////////
    // Récupération des mails du dossier affiché pour l'utilisateur courant
    $MailFolder = $this->CopsMailServices->getMailFolder($this->subOnglet);
    $CopsPlayer = CopsPlayer::getCurrentCopsPlayer();
    switch ($this->subOnglet) {
      case self::CST_FOLDER_DRAFT  :
      case self::CST_FOLDER_SENT   :
        $argRequest = array(
          self::FIELD_FOLDER_ID => $MailFolder->getField(self::FIELD_ID),
          self::FIELD_FROM_ID   => $CopsPlayer->getField(self::FIELD_ID),
        );
        $CopsMailJoints = $this->CopsMailServices->getMailJoints($argRequest);
      break;
      case self::CST_FOLDER_TRASH  :
        $argRequest = array(
          self::FIELD_FOLDER_ID => $MailFolder->getField(self::FIELD_ID),
          self::FIELD_TO_ID     => $CopsPlayer->getField(self::FIELD_ID),
          self::FIELD_FROM_ID   => $CopsPlayer->getField(self::FIELD_ID),
        );
        $CopsMailJoints = $this->CopsMailServices->getMailJoints($argRequest, true);
      break;
      case self::CST_FOLDER_INBOX  :
      case self::CST_FOLDER_EVENTS :
      case self::CST_FOLDER_ALERT  :
      case self::CST_FOLDER_SPAM   :
      default :
        $argRequest = array(
          self::FIELD_FOLDER_ID => $MailFolder->getField(self::FIELD_ID),
          self::FIELD_TO_ID     => $CopsPlayer->getField(self::FIELD_ID),
        );
        $CopsMailJoints = $this->CopsMailServices->getMailJoints($argRequest);
      break;
    }
    ///////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////
    // TODO : Va falloir gérer la pagination ici
    $nbMails = count($CopsMailJoints);
    $nbMailsPerPage = 10;
    $curPage = 1;
    $minMail = $nbMailsPerPage*($curPage-1)+1;
    $maxMail = min($nbMails, $nbMailsPerPage*$curPage);
    $strPagination = '';
    $strContent = '';
    if ($nbMails==0) {
      $strContent = '<tr><td class="text-center">Aucun message dans ce dossier.<br></td></tr>';
    } else {
      $strPagination = $minMail.'-'.$maxMail.' / '.$nbMails;
      while (!empty($CopsMailJoints)) {
        $CopsMailJoint = array_shift($CopsMailJoints);
        $strContent .= $CopsMailJoint->getBean()->getInboxRow();
      }
    }
    ///////////////////////////////////////////////////////////////////

    $attributes = array(
      // Titre du dossier affiché
      $this->arrSubOnglets[$this->subOnglet][self::FIELD_LABEL],
      // Nombre de messages dans le dossier affiché : 1-50/200
      $strPagination,
      // La liste des messages du dossier affiché
      $strContent,
      // Le slug du dossier affiché
      $this->subOnglet,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.05.02
   * @version 1.22.05.02
   */
  public function getFolderBlock()
  {
    /////////////////////////////////////////
    // Construction du panneau de gauche
    $strLeftPanel = '';
    $MailFolders = $this->CopsMailServices->getMailFolders();

    while (!empty($MailFolders)) {
      $MailFolder = array_shift($MailFolders);
      $strLeftPanel .= $MailFolder->getBean()->getMenuFolder($this->subOnglet);
    }
    /////////////////////////////////////////
    return $strLeftPanel;
  }

}
