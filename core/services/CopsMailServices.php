<?php
namespace core\services;

use core\daoimpl\CopsMailDaoImpl;

/**
 * Classe CopsMailServices
 * @author Hugues
 * @since 1.22.04.29
 * @version v1.23.07.29
 */
class CopsMailServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  public function __construct()
  {
    $this->initDao();
  }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    private function initDao(): void
    {
        if ($this->objDao==null) {
            $this->objDao = new CopsMailDaoImpl();
        }
    }

    ////////////////////////////////////
    // WP_7_COPS_MAIL
    ////////////////////////////////////
    /**
     * @since 1.22.05.04
     * @version v1.23.07.29
     */
    public function getMail($mailId=-1)
    {
        $attributes = [$mailId];
        $row = $this->objDao->getMail($attributes);
        return new CopsMail($row[0]);
    }
    ////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_MAIL_FOLDER
    ////////////////////////////////////
    /**
     * Retourne une liste de Folders
     * @param array $attributes :
     *      - ['slug'] : pattern de recherche
     * @since 1.22.05.04
     * @version v1.23.07.29
     */
    public function getMailFolders($attributes=[])
    {
        $objMailFolders = [];
        $prepAttributes = [
            isset($attributes[self::FIELD_ID]) ?? self::SQL_JOKER_SEARCH,
            isset($attributes[self::FIELD_SLUG]) ?? self::SQL_JOKER_SEARCH
        ];
        $rows = $this->objDao->getMailFolders($prepAttributes);
        while (!empty($rows)) {
            $objMailFolders[] = new CopsMailFolder(array_shift($rows));
        }
        return $objMailFolders;
    }
  
    /**
     * Retourne un Folder spécifique
     * @param string $slug
     * @since 1.22.05.04
     * @version 1.22.10.17
     */
    public function getMailFolder($slug=self::CST_MAIL_INBOX)
    {
        $objMailFolders = $this->getMailFolders([self::FIELD_SLUG=>$slug]);
        return array_shift($objMailFolders);
    }
    ////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_MAIL_USER
    ////////////////////////////////////
    /**
     * Retourne une liste de Users
     * @param array $attributes :
     *      - ['id'] : pattern de recherche
     * @since 1.22.05.04
     * @version v1.23.07.29
     */
    public function getMailUsers($attributes=[])
    {
        $prepAttributes = [
            (!isset($attributes[self::FIELD_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_ID]),
            (!isset($attributes[self::FIELD_MAIL]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_MAIL]),
            (!isset($attributes['copsId']) ? self::SQL_JOKER_SEARCH : $attributes['copsId'])
        ];
        $rows = $this->objDao->getMailUsers($prepAttributes);
        $objMailUsers = [];
        while (!empty($rows)) {
            $objMailUsers[] = new CopsMailUser(array_shift($rows));
        }
        return $objMailUsers;
    }
  
    /**
     * Retourne un User spécifique
     * @param int $id
     * @since 1.22.05.04
     * @version 1.22.10.17
     */
    public function getMailUser($id=-1)
    {
        if ($id==-1) {
            return new CopsMailUser();
        }
        $objMailUsers = $this->getMailUsers([$id]);
        return array_shift($objMailUsers);
    }
    ////////////////////////////////////

    ////////////////////////////////////
    // WP_7_COPS_MAIL_JOINT
    ////////////////////////////////////
    /**
     * Retourne une liste de Mail Joint
     * @param array $attributes :
     *      - ['id'] : pattern de recherche
     * @since 1.22.05.04
     * @version v1.23.07.29
     */
    public function getMailJoints($attributes=[], $blnBothFolders=false)
    {
        $prepAttributes = [
            (!isset($attributes[self::FIELD_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_ID]),
            (!isset($attributes[self::FIELD_TO_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_TO_ID]),
            (!isset($attributes[self::FIELD_FROM_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_FROM_ID]),
            (!isset($attributes[self::FIELD_FOLDER_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_FOLDER_ID]),
            (!isset($attributes[self::FIELD_LU]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_LU])
        ];
        $rows = $this->objDao->getMailJoints($prepAttributes, $blnBothFolders);
        $objMailJoints = [];
        while (!empty($rows)) {
            $objMailJoints[] = new CopsMailJoint(array_shift($rows));
        }
        return $objMailJoints;
    }
  
    /**
     * Retourne un MailJoint spécifique
     * @param int $id
     * @since 1.22.05.04
     * @version 1.22.10.17
     */
    public function getMailJoint($id=-1)
    {
        if ($id==-1) {
            return new CopsMailJoint();
        }
        $objMailJoints = $this->getMailJoints([self::FIELD_ID=>$id]);
        return array_shift($objMailJoints);
    }
  
    /**
     * @since 1.22.04.29
     * @version 1.22.10.17
     */
    public function getNombreMailsNonLus($attributes=[])
    {
        // On vérifie la présence du slug pour le Folder, puis on récupère le Folder associé
        $slug = ($attributes[self::FIELD_SLUG] ?? self::CST_MAIL_INBOX);
        $objMailFolder = $this->getMailFolder($slug);
        // On récupère le User courant
        $objCopsPlayer = CopsPlayer::getCurrentCopsPlayer();

        // On défini les critères de recherche selon le Folder interrogé.
        $blnBothFolders = false;
        $attributes = [self::FIELD_FOLDER_ID => $objMailFolder->getField(self::FIELD_ID), self::FIELD_LU        => 0];
        switch ($slug) {
            case self::CST_FOLDER_DRAFT  :
            case self::CST_FOLDER_SENT   :
                $attributes[self::FIELD_FROM_ID] = $objCopsPlayer->getField(self::FIELD_FROM_ID);
                break;
            case self::CST_MAIL_TRASH  :
                $attributes[self::FIELD_TO_ID] = $objCopsPlayer->getField(self::FIELD_ID);
                $attributes[self::FIELD_FROM_ID] = $objCopsPlayer->getField(self::FIELD_FROM_ID);
                $blnBothFolders = true;
                break;
            case self::CST_MAIL_INBOX  :
            case self::CST_FOLDER_EVENTS :
            case self::CST_FOLDER_ALERT  :
            case self::CST_FOLDER_SPAM   :
            default       :
                $attributes[self::FIELD_TO_ID] = $objCopsPlayer->getField(self::FIELD_ID);
                break;
        }

        $objMailJoints = $this->getMailJoints($attributes, $blnBothFolders);
        return is_countable($objMailJoints) ? count($objMailJoints) : 0;
    }
  
    /**
     * @since 1.22.05.01
     * @version v1.23.07.29
     */
    public function getPrevNextMailJoint($objCopsMailJoint, $prev=true)
    {
        $blnBothFolders=false;
        $attributes = [$objCopsMailJoint->getField(self::FIELD_ID)];

        switch ($objCopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG)) {
            case self::CST_FOLDER_DRAFT  :
            case self::CST_FOLDER_SENT   :
                $attributes[] = self::SQL_JOKER_SEARCH;
                $attributes[] = $objCopsMailJoint->getField(self::FIELD_FROM_ID);
                break;
            case self::CST_MAIL_TRASH  :
                $attributes[] = $objCopsMailJoint->getField(self::FIELD_TO_ID);
                $attributes[] = $objCopsMailJoint->getField(self::FIELD_FROM_ID);
                $blnBothFolders = true;
                break;
            case self::CST_MAIL_INBOX  :
            case self::CST_FOLDER_EVENTS :
            case self::CST_FOLDER_ALERT  :
            case self::CST_FOLDER_SPAM   :
            default       :
                $attributes[] = $objCopsMailJoint->getField(self::FIELD_TO_ID);
                $attributes[] = self::SQL_JOKER_SEARCH;
                break;
        }

        $attributes[] = $objCopsMailJoint->getField(self::FIELD_FOLDER_ID);
        $attributes[] = $objCopsMailJoint->getMail()->getField(self::FIELD_MAIL_DATE_ENVOI);

        $rows = $this->objDao->getPrevNextMailJoint($attributes, $blnBothFolders, $prev);
        if (empty($rows)) {
            return new CopsMailJoint();
        } else {
            return new CopsMailJoint(array_shift($rows));
        }
    }
    ////////////////////////////////////












    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function updateMailJoint($objCopsMailJoint)
    { $this->objDao->updateMailJoint($objCopsMailJoint); }

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function insertMailJoint($objCopsMailJoint)
    { $this->objDao->insertMailJoint($objCopsMailJoint); }

  ////////////////////////////////////

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function updateMail($objCopsMail)
    { $this->objDao->updateMail($objCopsMail); }

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function insertMail(&$objCopsMail)
    { $this->objDao->insertMail($objCopsMail); }

}
