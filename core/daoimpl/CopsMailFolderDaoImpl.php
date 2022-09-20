<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailFolderDaoImpl
 * @author Hugues
 * @since 1.22.05.08
 * @version 1.22.05.08
 */
class CopsMailFolderDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   * @since 1.22.05.08
   * @version 1.22.05.08
   */
  public function __construct()
  {
    ////////////////////////////////////
    // Définition des variables spécifiques
    $this->dbTable  = "wp_7_cops_mail_folder";
    ////////////////////////////////////
    parent::__construct();
  }

  /**
   * @since 1.22.05.08
   * @version 1.22.05.08
   */
  public function getMailFolders()
  {
    $request = $this->select."ORDER BY id ASC;";
    return MySQL::wpdbSelect($request);
  }

}
