<?php
namespace core\bean;

/**
 * Classe WpPageAdminMailBean
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.23.04.30
 */
class WpPageAdminMailBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        /////////////////////////////////////////
        // Définition des services
        $this->CopsMailServices = new CopsMailServices();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_INBOX;
        $this->titreOnglet = self::LABEL_MESSAGERIE;
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        
        /////////////////////////////////////////
        // Construction du menu
        $this->arrMenu = [
            self::CST_MAIL_INBOX  => [self::FIELD_ICON => 'inbox', self::FIELD_LABEL => self::LABEL_INBOX],
            self::CST_MAIL_TRASH  => [self::FIELD_ICON => 'trash-alt', self::FIELD_LABEL => self::LABEL_TRASH],
            /*
            self::CST_FOLDER_DRAFT  => array(self::FIELD_ICON => 'file-lines',    self::FIELD_LABEL => 'Brouillons'),
            self::CST_FOLDER_SENT   => array(self::FIELD_ICON => 'paper-plane',   self::FIELD_LABEL => 'Envoyés'),
            self::CST_FOLDER_EVENTS => [self::FIELD_ICON => 'calendar-days', self::FIELD_LABEL => self::LABEL_EVENTS],
            self::CST_FOLDER_ALERT  => array(self::FIELD_ICON => 'bell',         self::FIELD_LABEL => 'Notifications'),
            self::CST_FOLDER_SPAM   => array(self::FIELD_ICON => 'filter',        self::FIELD_LABEL => 'Indésirables'),
            */
            self::CST_MAIL_READ => [self::FIELD_LABEL => 'Lire'],
            self::CST_MAIL_WRITE => [self::FIELD_LABEL => 'Rédiger'],
        ];
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $buttonContent = $this->getLink($this->titreOnglet, parent::getOngletUrl(), self::CST_TEXT_WHITE);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDark)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
        
        $id = $this->initVar(self::FIELD_ID);
        if ($id!='') {
            $this->objCopsMailJoint = $this->CopsMailServices->getMailJoint($id);
            if ($this->objCopsMailJoint->getField(self::FIELD_TO_ID)==$this->CopsPlayer->getField(self::FIELD_ID)) {
                $this->objCopsMail = $this->objCopsMailJoint->getMail();
            } else {
                $this->objCopsMail = new CopsMail();
            }
        } else {
            $this->objCopsMailJoint = new CopsMailJoint();
        }
    }

  /**
   * @return string
   * @since 1.22.04.29
   * @version 1.22.10.19
   *
    public function getBoard()
    {
        // On devrait ici contrôler les actions et les effectuer
        // read ? -> On marque le message lu
        if ($this->subOnglet==self::CST_FOLDER_READ) {
            $this->CopsMailJoint = $this->CopsMailServices->getMailJoint($this->urlParams[self::FIELD_ID]);
            $this->CopsMailJoint->setField(self::FIELD_LU, 1);
            $this->CopsMailServices->updateMailJoint($this->CopsMailJoint);
        } elseif ($this->subOnglet==self::CST_FOLDER_WRITE) {
            if (isset($this->urlParams[self::FIELD_ID])) {
                $this->CopsMailJoint = $this->CopsMailServices->getMailJoint($this->urlParams[self::FIELD_ID]);
            } else {
                $this->CopsMailJoint = new CopsMailJoint();
            }
        }

        // trash ? -> On supprimer les messages
        if (isset($this->urlParams[self::CST_FOLDER_TRASH])) {
            $ids = explode(',', $this->urlParams['ids']);
            // On parcourt la liste des ids
            while (!empty($ids)) {
                $id = array_shift($ids);
                $objCopsMailJoint = $this->CopsMailServices->getMailJoint($id);
                // On vérifie que l'id est existant, qu'il appartient bien au folder actuel
                // et que le user actuel en est bien le destinataire
                if ($objCopsMailJoint->getField(self::FIELD_TO_ID)==2) {
                    if ($this->subOnglet!=self::CST_FOLDER_TRASH) {
                        // Si les contrôles sont okay, on déplace le message vers le folder trash.
                        $objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 6);
                    } else {
                        // Si le folder actuel est trash, on le supprime définitivement.
                        // (bon en fait, on le met dans le folder 10 qui n'existe pas)
                        $objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 10);
                    }
                    $this->CopsMailServices->updateMailJoint($objCopsMailJoint);
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
                self::FIELD_MAIL_DATE_ENVOI =>
                ($mailAction==self::CST_FOLDER_DRAFT ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s')),
            );

            if ($this->urlParams[self::FIELD_ID]!='') {
                $attributes[self::FIELD_ID] = $this->urlParams[self::FIELD_ID];
                $objCopsMail = new CopsMail($attributes);
                $this->CopsMailServices->updateMail($objCopsMail);
            } else {
                // On insère le nouveau mail dans cops_mail.
                // Toutefois, si c'est un draft, la dateEnvoi est à 0000-00-00 00:00:00
                $objCopsMail = new CopsMail($attributes);
                $this->CopsMailServices->insertMail($objCopsMail);
            }

            $attributes = array(self::FIELD_MAIL=>$this->urlParams['mailTo']);
            $objsCopsMailUser = $this->CopsMailServices->getMailUsers($attributes);
            $objCopsMailUser = array_shift($objsCopsMailUser);

            $attributes = array(
                self::FIELD_MAIL_ID     => $objCopsMail->getField(self::FIELD_ID),
                self::FIELD_TO_ID       => $objCopsMailUser->getField(self::FIELD_ID),
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
                $objCopsMailJoint = new CopsMailJoint($attributes);
                $this->CopsMailServices->insertMailJoint($objCopsMailJoint);
            } else {
                // Pour le destinataire (unique pour le moment)
                $attributes[self::FIELD_FOLDER_ID] = 1;
                $attributes[self::FIELD_LU] = 0;
                $objCopsMailJoint = new CopsMailJoint($attributes);
                $this->CopsMailServices->insertMailJoint($objCopsMailJoint);
                // Pour l'expéditeur
                $attributes[self::FIELD_FOLDER_ID] = 3;
                $attributes[self::FIELD_LU] = 1;
                $objCopsMailJoint = new CopsMailJoint($attributes);
                $this->CopsMailServices->insertMailJoint($objCopsMailJoint);
            }
        }
        return parent::getBoard();
    }

    /**
     * @since 1.22.04.29
     * @version 1.22.10.19
     *
    public function getOngletContent()
    {
        // Définir message précédent
        // Définir message suivant

        /////////////////////////////////////////
        // Construction du panneau de droite
        // On le fait avant le panneau de gauche, pour le cas où on afficherait un message non lu
        // qui deviendrait lu et donc ne doit plus remonter comme tel dans les badges
        switch ($this->subOnglet) {
            case self::CST_FOLDER_READ :
                $strRightPanel = $this->getReadMessageBlock();
                $strButtonRetour = '<a href="/admin?onglet=inbox&subOnglet='.$this->Folder->getField(self::FIELD_SLUG);
                $strButtonRetour .= '" class="btn btn-primary btn-block mb-3"><i class="fa-solid fa-backward"></i> ';
                $strButtonRetour .= $this->Folder->getField(self::FIELD_LABEL).'</a>';
                break;
            case self::CST_FOLDER_WRITE :
                $strRightPanel = $this->getWriteMessageBlock();
                $strButtonRetour  = '<a href="/admin?onglet=inbox" class="btn btn-primary btn-block mb-3">';
                $strButtonRetour .= '<i class="fa-solid fa-backward"></i> Retour</a>';
                break;
            default :
                $strRightPanel = $this->getFolderMessagesList();
                $strButtonRetour  = '<a href="/admin?onglet=inbox&subOnglet=write" class="btn btn-primary btn-block ';
                $strButtonRetour .= 'mb-3">'.self::LABEL_WRITE_MAIL.'</a>';
                break;
        }
        /////////////////////////////////////////

        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox.php';
        $attributes = array(
            // Contenu du panneau latéral gauche
            $this->getFolderBlock(),
            // Contenu du panneau principal
            $strRightPanel,
            // Eventuel bouton de retour si on est en train de lire ou rédiger un message
            $strButtonRetour,
        );
        return $this->getRender($urlTemplate, $attributes);
    }


    /**
     * @since 1.22.05.02
     * @version 1.22.10.19
     *
    public function getFolderMessagesList()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-messages.php';

        ///////////////////////////////////////////////////////////////////
        // Récupération des mails du dossier affiché pour l'utilisateur courant
        $objMailFolder = $this->CopsMailServices->getMailFolder($this->subOnglet);
        $objCopsPlayer = CopsPlayer::getCurrentCopsPlayer();
        switch ($this->subOnglet) {
            case self::CST_FOLDER_DRAFT  :
            case self::CST_FOLDER_SENT   :
                $argRequest = array(
                    self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
                    self::FIELD_FROM_ID   => $objCopsPlayer->getField(self::FIELD_ID),
                );
                $objsCopsMailJoint = $this->CopsMailServices->getMailJoints($argRequest);
                break;
            case self::CST_FOLDER_TRASH  :
                $argRequest = array(
                    self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
                    self::FIELD_TO_ID     => $objCopsPlayer->getField(self::FIELD_ID),
                    self::FIELD_FROM_ID   => $objCopsPlayer->getField(self::FIELD_ID),
                );
                $objsCopsMailJoint = $this->CopsMailServices->getMailJoints($argRequest, true);
                break;
            case self::CST_FOLDER_INBOX  :
            case self::CST_FOLDER_EVENTS :
            case self::CST_FOLDER_ALERT  :
            case self::CST_FOLDER_SPAM   :
            default :
                $argRequest = array(
                    self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
                    self::FIELD_TO_ID     => $objCopsPlayer->getField(self::FIELD_ID),
                );
                $objsCopsMailJoint = $this->CopsMailServices->getMailJoints($argRequest);
            break;
        }
        ///////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////
        // TODO : Va falloir gérer la pagination ici
        $nbMails = count($objsCopsMailJoint);
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
            while (!empty($objsCopsMailJoint)) {
                $objCopsMailJoint = array_shift($objsCopsMailJoint);
                $strContent .= $objCopsMailJoint->getBean()->getInboxRow();
            }
        }
        ///////////////////////////////////////////////////////////////////

        $attributes = array(
            // Titre du dossier affiché
            $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL],
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->slugSubOnglet,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    */
    
    /**
     * @return string
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getMenuContent()
    {
        /////////////////////////////////////////
        // On va définir la liste des éléments du menu de gauche.
        $menuContent = '';
        foreach ($this->arrMenu as $key => $arrMenu) {
            if (!isset($arrMenu[self::FIELD_ICON])) {
                continue;
            }
            $aContent = $this->getIcon($arrMenu[self::FIELD_ICON]).self::CST_NBSP.$arrMenu[self::FIELD_LABEL];
            $href = $this->getUrl([self::CST_SUBONGLET => $key]);
            $liContent = $this->getLink($aContent, $href, 'nav-link text-white');
            
            // Si le slug affiché vaut celui du menu ou qu'on est sur la vue par défaut est le menu est inbox
            $blnActive = ($this->slugSubOnglet==$key || $this->slugSubOnglet=='' && $key==self::CST_MAIL_INBOX);
            // Si on est sur read et que key vaut le folder du message affiché
            $objMailFolder = $this->objCopsMailJoint->getMailFolder();
            if ($objMailFolder!=null) {
                $slugFolder = $objMailFolder->getField('slug');
                if ($this->slugSubOnglet==self::CST_MAIL_READ && $slugFolder==$key) {
                    $blnActive = true;
                }
            }
            $strLiClass = 'nav-item'.($blnActive ? ' '.self::CST_ACTIVE : '');
            $menuContent .= $this->getBalise(self::TAG_LI, $liContent, [self::ATTR_CLASS=>$strLiClass]);
        }
        /////////////////////////////////////////
        return $menuContent;
    }
    
    /**
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public function getOngletContent()
    {
        ///////////////////////////////////////////////////////////////////
        // Bouton de retour
        $urlElements = [self::CST_SUBONGLET => $this->objCopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG)];
        $href = $this->getOngletUrl($urlElements);
        $label = self::LABEL_RETOUR;
        $btnContent  = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP;
        $btnContent .= $this->getLink($label, $href, self::CST_TEXT_WHITE);
        $btnAttributes = [
            self::ATTR_TITLE => $label,
            self::ATTR_CLASS => 'btn btn-default btn-primary col-4 mb-3'];
        $strButtonRetour = $this->getBalise(self::TAG_BUTTON, $btnContent, $btnAttributes);
        // Bouton de création d'un message
        $urlElements = [self::CST_SUBONGLET=>self::CST_WRITE];
        $href = $this->getOngletUrl($urlElements);
        $btnContent = $this->getLink(self::LABEL_WRITE_MAIL, $href, self::CST_TEXT_WHITE);
        $btnAttributes = [
            self::ATTR_TITLE => self::LABEL_WRITE_MAIL,
            self::ATTR_CLASS => 'btn btn-default btn-primary col-8 mb-3'
        ];
        $strButtonRetour .= $this->getBalise(self::TAG_BUTTON, $btnContent, $btnAttributes);

        if ($this->slugSubOnglet==self::CST_MAIL_READ) {
            $mainContent = $this->getReadMessageBlock();
        } elseif ($this->slugSubOnglet==self::CST_MAIL_WRITE) {
            $mainContent = $this->getWriteMessageBlock();
        } else {
            $mainContent = '';
        }
        
        $urlTemplate = self::WEB_PPFS_ONGLET;
        $attributes = [
            // L'id de la page
            'section-inbox',
            // Le bouton éventuel de création / retour...
            '<div class="btn-group btn-block">'.$strButtonRetour.'</div>',
            // Le nom du bloc du menu de gauche
            self::LABEL_MESSAGERIE,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    
    public function dealWithDraftMessage()
    {
        /**
        Array
        (
            [mailFrom] => 3
            [mailTo] => cops001@cops.gov
            [mailSubject] => Test envoi
            [compose-textarea] =>
            [mailContent] => Content Test<br>
            [writeAction] => draft
            [id] =>
        )
        */
        // On créé un CopsMail
        $objCopsMail = new CopsMail();
        $objCopsMail->setField(self::FIELD_MAIL_SUBJECT, stripslashes((string) static::fromPost('mailSubject')));
        $objCopsMail->setField(self::FIELD_MAIL_CONTENT, stripslashes((string) static::fromPost('mailContent')));
        $objCopsMail->setField(self::FIELD_MAIL_DATE_ENVOI, static::getCopsDate('Y-m-d h:i:s'));
        $this->CopsMailServices->insertMail($objCopsMail);
        // Puis on créé un CopsMailJoint
        $objCopsMailJoint = new CopsMailJoint();
        $objCopsMailJoint->setField(self::FIELD_MAIL_ID, $objCopsMail->getField(self::FIELD_ID));
        $objCopsMailJoint->setField(self::FIELD_TO_ID, 0); // TODO
        $objCopsMailJoint->setField(self::FIELD_FROM_ID, static::fromPost('mailFrom'));
        $objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 2);
        $objCopsMailJoint->setField(self::FIELD_LU, 0);
        $objCopsMailJoint->setField(self::FIELD_NB_PJS, 0);
        $this->CopsMailServices->insertMailJoint($objCopsMailJoint);
    }

    /**
     * @since 1.22.05.04
     * @version 1.22.11.12
     */
    public function getWriteMessageBlock()
    {
        /////////////////////////////////////////////////////////////////
        // Vient-on de poster un nouveau message ?
        if (static::fromPost(self::CST_WRITE_ACTION)==self::CST_FOLDER_DRAFT) {
            $this->dealWithDraftMessage();
        }
        
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, 'Rédiger', $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////////////////////////////
        
        $objCopsMail = $this->objCopsMailJoint->getMail();
        $objCopsMailUser = $this->CopsMailServices->getMailUser($this->objCopsMailJoint->getField(self::FIELD_TO_ID));
     
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-write.php';
        $attributes = [
            // La liste des expéditeurs, visible ou non, si on est admin (liste d'options)
            (static::isAdmin() ? '' : ' disabled'),
            // La liste des expéditeurs, son contenu, si on est admin (liste d'options)
            $this->getExpediteurOptions(),
            // Les destinataires par défaut (dans le cas d'un reply par exemple)
            '',
            //$objCopsMailUser->getField(self::FIELD_MAIL),
            // Le sujet par défaut (dans le cas d'un reply ou d'un share)
            '',
            //$objCopsMail->getField(self::FIELD_MAIL_SUBJECT),
            // Le message par défaut (dans le textarea, dans le cas d'un reply ou d'un share)
            '',
            //$objCopsMail->getField(self::FIELD_MAIL_CONTENT),
            // Le message par défaut stylisée dans le champ de saisie dans le cas d'un reply ou d'un share)
            '',
            //$objCopsMail->getField(self::FIELD_MAIL_CONTENT),
            // L'id éventuel du mail (dans le cas de la rédaction d'un draft)
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
     }
     
     /**
      * @return string
      * @since v1.22.11.12
      * @version v1.22.11.12
      */
     public function getExpediteurOptions()
     {
         $strExpediteurOptions = '';
         $id = $this->CopsPlayer->getField(self::FIELD_ID);
         $objsMailUser = $this->CopsMailServices->getMailUsers(['copsId'=>$id]);

         $objMailUser = array_shift($objsMailUser);
         if (static::isAdmin()) {
             $strExpediteurOptions .= '<option value="1">Ressources Humaines</option>';
             $strExpediteurOptions .= '<option value="2">COPS 101</option>';
         }
         $label = $objMailUser->getField('user');
         $optAttributes = [self::ATTR_VALUE=>$objMailUser->getField(self::FIELD_ID)];
         $strExpediteurOptions .= $this->getBalise(self::TAG_OPTION, $label, $optAttributes);
         return $strExpediteurOptions;
     }
     
    /**
     * @since 1.22.05.06
     * @version 1.22.11.11
     */
    public function getReadMessageBlock()
    {
        //////////////////////////////////////////////////
        // On marque le message comme lu
        // Il risque cependant de demeurer compté comme non lu dans le Header sur cet affichage
        $this->objCopsMailJoint->setField(self::FIELD_LU, 1);
        $this->CopsMailServices->updateMailJoint($this->objCopsMailJoint);
        //////////////////////////////////////////////////
        
        $spanAttributes = [self::ATTR_CLASS=>self::CST_TEXT_WHITE];
        $buttonContent = $this->getBalise(self::TAG_SPAN, 'Lecture', $spanAttributes);
        $buttonAttributes = [self::ATTR_CLASS=>($this->btnDisabled)];
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);

        //////////////////////////////////////////////////
        // Construction de la chaine de réception
        // 'From: tintin@herge.fr<br>Cc: haddock@herge.fr'
        $strAddress = 'De : '.$this->objCopsMailJoint->getAuteur()->getField(self::FIELD_MAIL);
        //////////////////////////////////////////////////

        //////////////////////////////////////////////////
        // Construction de la date d'envoi
        $strDateEnvoi = $this->objCopsMail->getDateEnvoiFormate();
        //////////////////////////////////////////////////
        
        //////////////////////////////////////////////////
        // Les différents boutons
        
        // Url de suppression
        $urlElements = [
            self::CST_SUBONGLET => self::CST_MAIL_TRASH,
            self::FIELD_ID => $this->objCopsMailJoint->getField(self::FIELD_ID)
        ];
        $urlRemove = $this->getOngletUrl($urlElements);
        
        // Les boutons centraux en haut :
        $buttonContent = $this->getLink($this->getIcon('trash-alt'), $urlRemove, self::CST_TEXT_WHITE);
        $strButton = $this->getButton($buttonContent, [self::ATTR_TITLE=>self::LABEL_DELETE]);
        $strButtonCentral = $this->getDiv($strButton, [self::ATTR_CLASS=>'btn-group']);
        
        // Les boutons en bas à gauche
        $buttonAttributes = [
            self::ATTR_TYPE => self::TAG_BUTTON,
            self::ATTR_CLASS => 'btn btn-default',
            self::ATTR_TITLE => self::LABEL_DELETE
        ];
        $strButtonLeft = $this->getBalise(self::TAG_BUTTON, $buttonContent.' '.self::LABEL_DELETE, $buttonAttributes);
        
        /**
         * TODO
      <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm" title="Répondre"><a href="#" class="text-white">
            <i class="fa-solid fa-reply"></i></a></button>
        <button type="button" class="btn btn-default btn-sm" title="Transférer"><a href="#" class="text-white">
            <i class="fa-solid fa-share"></i></a></button>
      </div>
      <button type="button" class="btn btn-default btn-sm" title="Imprimer"><a href="#" class="text-white">
        <i class="fa-solid fa-print"></i></a></button>
      </div>
        
      <button type="button" class="btn btn-default"><a href="#" class="text-white">
        <i class="fa-solid fa-reply"></i> Répondre</a></button>
      <button type="button" class="btn btn-default"><a href="#" class="text-white">
        <i class="fa-solid fa-share"></i> Transférer</a></button>
    </div>
    <button type="button" class="btn btn-default"><a href="#" class="text-white">
        <i class="fa-solid fa-print"></i> Imprimer</a></button>
         *
         */
        //////////////////////////////////////////////////
        
        //////////////////////////////////////////////////
        // CardTools (navigation pour passer à d'autres mails
        
        // Mail précédent ou folder du mail si aucun mail précédent
        $objPrevMailJoint = $this->CopsMailServices->getPrevNextMailJoint($this->objCopsMailJoint);
        $prevId = $objPrevMailJoint->getField(self::FIELD_ID);
        if ($prevId=='') {
            $arrUrlElements = [
                self::CST_SUBONGLET => $this->objCopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG)
            ];
        } else {
            $arrUrlElements = [self::CST_SUBONGLET => self::CST_MAIL_READ, self::FIELD_ID => $prevId];
        }
        $url = $this->getOngletUrl($arrUrlElements);
        $aContent = '<i class="fa-solid fa-chevron-left"></i>';
        $buttonContent = $this->getLink($aContent, $url, 'btn btn-tool', ['title'=>'Précédent']);
        $cardTools = $this->getButton($buttonContent, [self::ATTR_CLASS=>'btn-dark']);
        
        // Mail suivant ou folder du mail si aucun mail suivant
        $objPrevMailJoint = $this->CopsMailServices->getPrevNextMailJoint($this->objCopsMailJoint, false);
        $nextId = $objPrevMailJoint->getField(self::FIELD_ID);
        if ($nextId=='') {
            $arrUrlElements = [
                self::CST_SUBONGLET => $this->objCopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG)
            ];
        } else {
            $arrUrlElements = [self::CST_SUBONGLET => self::CST_MAIL_READ, self::FIELD_ID => $nextId];
        }
        $url = $this->getOngletUrl($arrUrlElements);
        $aContent = '<i class="fa-solid fa-chevron-right"></i>';
        $buttonContent = $this->getLink($aContent, $url, 'btn btn-tool', ['title'=>'Suivant']);
        $cardTools .= $this->getButton($buttonContent, [self::ATTR_CLASS=>'btn-dark']);
        //////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-read.php';
        
        $attributes = [
            // Titre du mail
            $this->objCopsMail->getField(self::FIELD_MAIL_SUBJECT),
            // From
            $strAddress,
            // Date envoi
            $strDateEnvoi,
            //'15 Feb. 2015 11:03 PM',
            // Corps du message
            $this->objCopsMail->getField(self::FIELD_MAIL_CONTENT),
            // Liste des pièces jointes
            '',
            // les Card Tools
            $cardTools,
            // Les boutons en entête du mail lu
            $strButtonCentral,
            // Les boutons en bas à droite du mail lu
            '',
            // Les boutons en bas à gauche du mail lu
            $strButtonLeft,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
     
    /**
     * @since v1.22.11.11
     * @version v1.22.11.11
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        return match ($slugSubContent) {
            self::CST_MAIL_TRASH => new WpPageAdminMailTrashBean(),
            self::CST_MAIL_READ, self::CST_MAIL_WRITE => new WpPageAdminMailBean(),
            default => new WpPageAdminMailInboxBean(),
        };
    }
    
    /**
     * @param array $urlElements
     * @return string
     * @since v1.22.11.12
     * @version v1.22.11.12
     */
    public function getRefreshUrl($urlElements=[])
    {
        // Si catSlug est défini et non présent dans $urlElements, il doit être repris.
        if ($this->catSlug!='' && !isset($urlElements[self::CST_CAT_SLUG])) {
            $urlElements[self::CST_CAT_SLUG] = $this->catSlug;
        }
        // Si curPage est défini et non présent dans $urlElements, il doit être repris.
        if ($this->curPage!='' && !isset($urlElements[self::CST_CURPAGE])) {
            $urlElements[self::CST_CURPAGE] = $this->curPage;
        }
        return $this->getUrl($urlElements);
    }
    
    /**
     * @since 1.22.11.17
     * @version 1.22.11.17
     */
    public function getOngletContentMutual($labelDossier, $idPage)
    {
        ///////////////////////////////////////////////////////////////////
        // Bouton de création d'un nouveau message
        $urlElements = [self::CST_SUBONGLET=>self::CST_WRITE];
        $href = $this->getOngletUrl($urlElements);
        $strButtonRetour = $this->getLink(self::LABEL_WRITE_MAIL, $href, 'btn btn-primary btn-block mb-3');
        
        ///////////////////////////////////////////////////////////////////
        // Récupération des mails du dossier affiché pour l'utilisateur courant
        $objMailFolder = $this->CopsMailServices->getMailFolder($this->subOnglet);
        $argRequest = [
            self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
            self::FIELD_TO_ID => $this->CopsPlayer->getField(self::FIELD_ID)]
            ;
        $objsCopsMailJoint = $this->CopsMailServices->getMailJoints($argRequest);
        
        $strContent = '';
        if (empty($objsCopsMailJoint)) {
            $strContent = '<tr><td class="text-center" colspan="3">'.self::LABEL_NO_RESULT.'</td></tr>';
        } else {
            ///////////////////////////////////////////////////////////////////
            // Pagination
            $strPagination = $this->buildPagination($objsCopsMailJoint);
            foreach ($objsCopsMailJoint as $objCopsMailJoint) {
                $strContent .= $objCopsMailJoint->getBean()->getInboxRow();
            }
        }
        ///////////////////////////////////////////////////////////////////
        
        //////////////////////////////////////////////////////////////
        // Construction de la liste
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-inbox-messages.php';
        $attributes = [
            // Titre du dossier affiché
            $labelDossier,
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->slugSubOnglet,
        ];
        $mainContent = $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////////
        
        $urlTemplate = self::WEB_PPFS_ONGLET;
        $attributes = [
            // L'id de la page
            $idPage,
            // Le bouton éventuel de création / retour...
            $strButtonRetour,
            // Le nom du bloc du menu de gauche
            self::LABEL_MESSAGERIE,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        ];
        return $this->getRender($urlTemplate, $attributes);
    }
    
}
