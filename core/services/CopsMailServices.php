<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailServices
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.05.10
 */
class CopsMailServices extends LocalServices
{
  //////////////////////////////////////////////////
  // CONSTRUCT
  //////////////////////////////////////////////////
  /**
   * Class constructor
   * @version 1.22.04.29
   * @since 1.22.04.29
   */
  public function __construct()
  {
    $this->Dao = new CopsMailDaoImpl();
  }

  //////////////////////////////////////////////////
  // METHODS
  //////////////////////////////////////////////////

  ////////////////////////////////////
  // WP_7_COPS_MAIL
  ////////////////////////////////////
  /**
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getMail($mailId=-1)
  {
    $attributes = array($mailId);
    $row = $this->Dao->getMail($attributes);
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
   * @version 1.22.05.04
   */
  public function getMailFolders($attributes=array())
  {
    $prepAttributes = array(
      (!isset($attributes[self::FIELD_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_ID]),
      (!isset($attributes[self::FIELD_SLUG]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_SLUG]),
    );
    $rows = $this->Dao->getMailFolders($prepAttributes);
    $MailFolders = array();
    while (!empty($rows)) {
      $MailFolders[] = new CopsMailFolder(array_shift($rows));
    }
    return $MailFolders;
  }
  /**
   * Retourne un Folder spécifique
   * @param string $slug
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getMailFolder($slug=self::CST_FOLDER_INBOX)
  {
    $MailFolders = $this->getMailFolders(array(self::FIELD_SLUG=>$slug));
    return array_shift($MailFolders);
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
   * @version 1.22.05.10
   */
  public function getMailUsers($attributes=array())
  {
    $prepAttributes = array(
      (!isset($attributes[self::FIELD_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_ID]),
      (!isset($attributes[self::FIELD_MAIL]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_MAIL]),
    );
    $rows = $this->Dao->getMailUsers($prepAttributes);
    $MailUsers = array();
    while (!empty($rows)) {
      $MailUsers[] = new CopsMailUser(array_shift($rows));
    }
    return $MailUsers;
  }
  /**
   * Retourne un User spécifique
   * @param int $id
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getMailUser($id=-1)
  {
    if ($id==-1) {
      return new CopsMailUser();
    }
    $MailUsers = $this->getMailUsers(array($id));
    return array_shift($MailUsers);
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
   * @version 1.22.05.04
   */
  public function getMailJoints($attributes=array(), $blnBothFolders=false)
  {
    $prepAttributes = array(
      (!isset($attributes[self::FIELD_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_ID]),
      (!isset($attributes[self::FIELD_TO_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_TO_ID]),
      (!isset($attributes[self::FIELD_FROM_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_FROM_ID]),
      (!isset($attributes[self::FIELD_FOLDER_ID]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_FOLDER_ID]),
      (!isset($attributes[self::FIELD_LU]) ? self::SQL_JOKER_SEARCH : $attributes[self::FIELD_LU]),
    );
    $rows = $this->Dao->getMailJoints($prepAttributes, $blnBothFolders);
    $MailJoints = array();
    while (!empty($rows)) {
      $MailJoints[] = new CopsMailJoint(array_shift($rows));
    }
    return $MailJoints;
  }
  /**
   * Retourne un MailJoint spécifique
   * @param int $id
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getMailJoint($id=-1)
  {
    if ($id==-1) {
      return new CopsMailJoint();
    }
    $MailJoints = $this->getMailJoints(array(self::FIELD_ID=>$id));
    return array_shift($MailJoints);
  }
  /**
   * @since 1.22.04.29
   * @version 1.22.05.04
   */
  public function getNombreMailsNonLus($attributes=array())
  {
    // On vérifie la présence du slug pour le Folder, puis on récupère le Folder associé
    $slug = (isset($attributes[self::FIELD_SLUG]) ? $attributes[self::FIELD_SLUG] : self::CST_FOLDER_INBOX);
    $MailFolder = $this->getMailFolder($slug);
    // On récupère le User courant
    $CopsPlayer = CopsPlayer::getCurrentCopsPlayer();

    // On défini les critères de recherche selon le Folder interrogé.
    $blnBothFolders = false;
    $attributes = array(
      self::FIELD_FOLDER_ID => $MailFolder->getField(self::FIELD_ID),
      self::FIELD_LU        => 0,
    );
    switch ($slug) {
      case self::CST_FOLDER_DRAFT  :
      case self::CST_FOLDER_SENT   :
        $attributes[self::FIELD_FROM_ID] = $CopsPlayer->getField(self::FIELD_FROM_ID);
      break;
      case self::CST_FOLDER_TRASH  :
        $attributes[self::FIELD_TO_ID] = $CopsPlayer->getField(self::FIELD_ID);
        $attributes[self::FIELD_FROM_ID] = $CopsPlayer->getField(self::FIELD_FROM_ID);
        $blnBothFolders = true;
      break;
      case self::CST_FOLDER_INBOX  :
      case self::CST_FOLDER_EVENTS :
      case self::CST_FOLDER_ALERT  :
      case self::CST_FOLDER_SPAM   :
      default       :
        $attributes[self::FIELD_TO_ID] = $CopsPlayer->getField(self::FIELD_ID);
      break;
    }

    $MailJoints = $this->getMailJoints($attributes, $blnBothFolders);
    return count($MailJoints);
  }
  /**
   * @since 1.22.05.01
   * @version 1.22.05.04
   */
  public function getPrevNextMailJoint($CopsMailJoint, $prev=true)
  {
    $blnBothFolders=false;
    $attributes = array(
      $CopsMailJoint->getField(self::FIELD_ID),
    );

    switch ($CopsMailJoint->getMailFolder()->getField(self::FIELD_SLUG)) {
      case self::CST_FOLDER_DRAFT  :
      case self::CST_FOLDER_SENT   :
        $attributes[] = self::SQL_JOKER_SEARCH;
        $attributes[] = $CopsMailJoint->getField(self::FIELD_FROM_ID);
      break;
      case self::CST_FOLDER_TRASH  :
        $attributes[] = $CopsMailJoint->getField(self::FIELD_TO_ID);
        $attributes[] = $CopsMailJoint->getField(self::FIELD_FROM_ID);
        $blnBothFolders = true;
      break;
      case self::CST_FOLDER_INBOX  :
      case self::CST_FOLDER_EVENTS :
      case self::CST_FOLDER_ALERT  :
      case self::CST_FOLDER_SPAM   :
      default       :
        $attributes[] = $CopsMailJoint->getField(self::FIELD_TO_ID);
        $attributes[] = self::SQL_JOKER_SEARCH;
      break;
    }

    $attributes[] = $CopsMailJoint->getField(self::FIELD_FOLDER_ID);
    $attributes[] = $CopsMailJoint->getMail()->getField(self::FIELD_MAIL_DATE_ENVOI);

    $rows = $this->Dao->getPrevNextMailJoint($attributes, $blnBothFolders, $prev);
    if (empty($rows)) {
      return new CopsMailJoint();
    } else {
      return new CopsMailJoint(array_shift($rows));
    }
  }
  ////////////////////////////////////












  public function updateMailJoint($CopsMailJoin)
  {
    $this->Dao->updateMailJoint($CopsMailJoin);
  }

  /**
   * @since 1.22.05.10
   * @version 1.22.05.10
   */
  public function insertMailJoint($CopsMailJoint)
  { $this->Dao->insertMailJoint($CopsMailJoint); }

  ////////////////////////////////////

  /**
   * @since 1.22.05.10
   * @version 1.22.05.10
   */
  public function updateMail($CopsMail)
  { $this->Dao->updateMail($CopsMail); }

  /**
   * @since 1.22.05.10
   * @version 1.22.05.10
   */
  public function insertMail(&$CopsMail)
  { $this->Dao->insertMail($CopsMail); }

}
