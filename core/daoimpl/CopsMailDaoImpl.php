<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailDaoImpl
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.10.17
 */
class CopsMailDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.04.29
   * @version 1.22.04.29
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    $this->dbTable      = "wp_7_cops_mail";
    $this->dbTable_cmf  = "wp_7_cops_mail_folder";
    $this->dbTable_cmj  = "wp_7_cops_mail_joint";
    $this->dbTable_cmu  = "wp_7_cops_mail_user";
    $this->dbTable_cmp  = "wp_7_cops_mail_pj";
    $this->dbTable_cmpj = "wp_7_cops_mail_pj_joint";
    ////////////////////////////////////

    parent::__construct();
  }

    ////////////////////////////////////
    // WP_7_COPS_MAIL
    ////////////////////////////////////
    /**
     * @since 1.22.04.29
     * @version 1.22.05.04
     */
    public function getMail($prepObject)
    {
        $request  = $this->select;
        $request .= "WHERE id = '%s';";
        $prepSql  = MySQL::wpdbPrepare($request, $prepObject);
        return MySQL::wpdbSelect($prepSql);
    }
    ////////////////////////////////////

  ////////////////////////////////////
  // WP_7_COPS_MAIL_FOLDER
  ////////////////////////////////////
  /**
   * @since 1.22.05.01
   * @version 1.22.05.04
   */
  public function getMailFolders($attributes)
  {
    $request  = $this->getSelect($this->dbTable_cmf);
    $request .= "FROM ".$this->dbTable_cmf." ";
    $request .= "WHERE id LIKE '%s' ";
    $request .= "AND slug LIKE '%s' ";
    $request .= "ORDER BY id ASC;";
    $prepSql  = MySQL::wpdbPrepare($request, $attributes);
    return MySQL::wpdbSelect($prepSql);
  }
  ////////////////////////////////////

  ////////////////////////////////////
  // WP_7_COPS_MAIL_USER
  ////////////////////////////////////
  /**
   * @since 1.22.05.01
   * @version 1.22.05.10
   */
  public function getMailUsers($attributes)
  {
    $request  = $this->getSelect($this->dbTable_cmu);
    $request .= "FROM ".$this->dbTable_cmu." ";
    $request .= "WHERE id LIKE '%s'";
    $request .= "AND mail LIKE '%s';";
    $prepSql  = MySQL::wpdbPrepare($request, $attributes);
    return MySQL::wpdbSelect($prepSql);
  }
  ////////////////////////////////////

  ////////////////////////////////////
  // WP_7_COPS_MAIL_JOINT
  ////////////////////////////////////
  /**
   * @since 1.22.05.04
   * @version 1.22.05.04
   */
  public function getMailJoints($attributes, $blnBothFolders)
  {
    $request  = "SELECT cmj.id AS id, mailId, toId, fromId, folderId, lu, nbPjs ";
    $request .= "FROM ".$this->dbTable_cmj." AS cmj ";
    $request .= "INNER JOIN ".$this->dbTable." AS cm ON cmj.mailId=cm.id ";
    $request .= "WHERE cmj.id LIKE '%s' ";
    if ($blnBothFolders) {
      $request .= "AND (toId LIKE '%s' OR fromId LIKE '%s') ";
    } else {
      $request .= "AND toId LIKE '%s' ";
      $request .= "AND fromId LIKE '%s' ";
    }
    $request .= "AND folderId LIKE '%s' ";
    $request .= "AND lu LIKE '%s' ";
    $request .= "ORDER BY mail_dateEnvoi DESC ;";
    $prepSql  = MySQL::wpdbPrepare($request, $attributes);
    return MySQL::wpdbSelect($prepSql);
  }
  /**
   * @since 1.22.05.01
   * @version 1.22.05.04
   */
  public function getPrevNextMailJoint($attributes, $blnBothFolders, $prev)
  {
    $request  = "SELECT cmj.id AS id, mailId, toId, fromId, folderId, lu, nbPjs ";
    $request .= "FROM ".$this->dbTable_cmj." AS cmj ";
    $request .= "INNER JOIN ".$this->dbTable." AS cm ON cmj.mailId=cm.id ";
    $request .= "WHERE cmj.id <> '%s' ";
    if ($blnBothFolders) {
      $request .= "AND (toId LIKE '%s' OR fromId LIKE '%s') ";
    } else {
      $request .= "AND toId LIKE '%s' ";
      $request .= "AND fromId LIKE '%s' ";
    }
    $request .= "AND folderId LIKE '%s' ";
    if ($prev) {
      $request .= "AND mail_dateEnvoi <= '%s' ";
      $request .= "ORDER BY mail_dateEnvoi DESC ";
    } else {
      $request .= "AND mail_dateEnvoi >= '%s' ";
      $request .= "ORDER BY mail_dateEnvoi ASC ";
    }
    $request .= "LIMIT 1;";
    $prepSql  = MySQL::wpdbPrepare($request, $attributes);
    return MySQL::wpdbSelect($prepSql);
  }
  ////////////////////////////////////









    /**
     * @since 1.22.04.30
     * @version 1.22.10.17
     */
    public function updateMailJoint($objMailJoint)
    {
        ////////////////////////////////////
        // Récupération des champs de l'objet en base
        $arrFields = array();
        $rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable_cmj.";");
        foreach ($rows as $row) {
            $arrFields[] = $row->Field;
        }
        ////////////////////////////////////

        $prepObject = array();
        $request  = "UPDATE ".$this->dbTable_cmj." SET ";
        foreach ($arrFields as $field) {
            $request .= $field."='%s', ";
            $prepObject[] = $objMailJoint->getField($field);
        }
        $request = substr($request, 0, -2)." WHERE id = '%s';";
        $prepObject[] = $objMailJoint->getField(self::FIELD_ID);

        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
    }
    ////////////////////////////////////

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function insertMailJoint(&$objMailJoint)
    {
        ////////////////////////////////////
        // Récupération des champs de l'objet en base
        $arrFields = array();
        $rows = MySQL::wpdbSelect("DESCRIBE ".$this->dbTable_cmj.";");
        foreach ($rows as $row) {
            if ($row->Field=='id') {
                continue;
            }
            $arrFields[] = $row->Field;
        }
        ////////////////////////////////////

        $prepObject = array();
        $request  = "INSERT INTO ".$this->dbTable_cmj." (";
        $requestValues = '';
        foreach ($arrFields as $field) {
            $request        .= $field.", ";
            $requestValues  .= "'%s', ";
            $prepObject[] = $objMailJoint->getField($field);
        }
        $request = substr($request, 0, -2).") VALUES (".substr($requestValues, 0, -2).");";

        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
        $objMailJoint->setField(self::FIELD_ID, MySQL::getLastInsertId());
    }

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function updateMail($objMail)
    {
        ////////////////////////////////////
        // Récupération des champs de l'objet en base
        $arrFields = $this->getFields();
        ////////////////////////////////////

        $prepObject = array();
        $request  = "UPDATE ".$this->dbTable." SET ";
        foreach ($arrFields as $field) {
            $request .= $field."='%s', ";
            $prepObject[] = $objMail->getField($field);
        }
        $request = substr($request, 0, -2)." WHERE id = '%s';";
        $prepObject[] = $objMail->getField(self::FIELD_ID);

        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
    }
    ////////////////////////////////////

    /**
     * @since 1.22.05.10
     * @version 1.22.10.17
     */
    public function insertMail(&$objMail)
    {
        ////////////////////////////////////
        // Récupération des champs de l'objet en base
        $arrFields = $this->getFields();
        ////////////////////////////////////

        $prepObject = array();
        $request  = "INSERT INTO ".$this->dbTable." (";
        $requestValues = '';
        foreach ($arrFields as $field) {
            $request        .= $field.", ";
            $requestValues  .= "'%s', ";
            $prepObject[] = $objMail->getField($field);
        }
        $request = substr($request, 0, -2).") VALUES (".substr($requestValues, 0, -2).");";

        $sql = MySQL::wpdbPrepare($request, $prepObject);
        MySQL::wpdbQuery($sql);
        $objMail->setField(self::FIELD_ID, MySQL::getLastInsertId());
    }

}
