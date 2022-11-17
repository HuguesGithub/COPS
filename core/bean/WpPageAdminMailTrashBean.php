<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminMailTrashBean
 * @author Hugues
 * @since 1.22.11.12
 * @version 1.22.11.12
 */
class WpPageAdminMailTrashBean extends WpPageAdminMailBean
{
    public function __construct()
    {
        parent::__construct();
        
        $spanAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $buttonContent = $this->getBalise(self::TAG_SPAN, self::LABEL_TRASH, $spanAttributes);
        $buttonAttributes = array(self::ATTR_CLASS=>($this->btnDisabled));
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Si le folder du mail n'est pas la Corbeille, on le met dans la Corbeille
        if ($this->objCopsMailJoint->getField(self::FIELD_FOLDER_ID)!=6) {
            $this->objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 6);
        } else {
            // Sinon, on le supprime définitivement
            $this->objCopsMailJoint->setField(self::FIELD_FOLDER_ID, 10);
        }
        $this->CopsMailServices->updateMailJoint($this->objCopsMailJoint);
    }
    
    /**
     * @since 1.22.11.12
     * @version 1.22.11.12
     */
    public function getOngletContent()
    {
        ///////////////////////////////////////////////////////////////////
        // Bouton de création d'un nouveau message
        $urlElements = array(self::CST_SUBONGLET=>self::CST_WRITE);
        $href = $this->getOngletUrl($urlElements);
        $strButtonRetour = $this->getLink('Rédiger un message', $href, 'btn btn-primary btn-block mb-3');
        
        ///////////////////////////////////////////////////////////////////
        // Récupération des mails du dossier affiché pour l'utilisateur courant
        $objMailFolder = $this->CopsMailServices->getMailFolder($this->slugSubOnglet);
        $argRequest = array(
            self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID),
            self::FIELD_TO_ID => $this->CopsPlayer->getField(self::FIELD_ID),
        );
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
        $attributes = array(
            // Titre du dossier affiché
            self::LABEL_TRASH,
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->slugSubOnglet,
        );
        $mainContent = $this->getRender($urlTemplate, $attributes);
        //////////////////////////////////////////////////////////////
        
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-onglet.php';
        $attributes = array(
            // L'id de la page
            'section-trash',
            // Le bouton éventuel de création / retour...
            $strButtonRetour,
            // Le nom du bloc du menu de gauche
            self::LABEL_MESSAGERIE,
            // La liste des éléments du menu de gauche
            $this->getMenuContent(),
            // Le contenu de la liste relative à l'élément sélectionné dans le menu de gauche
            $mainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
