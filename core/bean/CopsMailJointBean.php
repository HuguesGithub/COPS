<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsMailJointBean
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.04.30
 */
class CopsMailJointBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsMailJoint = ($Obj==null ? new CopsMailJoint() : $Obj);
    $this->CopsMailServices = new CopsMailServices();
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.30
   */
  public function getInboxRow()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-inbox-row.php';

    $id           = $this->CopsMailJoint->getField(self::FIELD_ID);
    $CopsMail     = $this->CopsMailServices->getMail($this->CopsMailJoint->getField('mailId'));
    $CopsMailUser = $this->CopsMailServices->getMailUser($this->CopsMailJoint->getField('fromId'));
    $auteur       = $CopsMailUser->getField('user');
    $sujet        = $CopsMail->getField('mail_subject');
    $bln_NonLu    = ($this->CopsMailJoint->getField('lu')==0);
    $bln_HasPjs   = ($this->CopsMailJoint->getField('nbPjs')!=0);
    $since        = $CopsMail->getField('mail_dateEnvoi');
    // TODO
    $strSince = $since;//'5 mins ago';

    $attributes = array(
      // Bordure gauche pour mettre en évidence les nouveaux mails
      ($bln_NonLu ? ' class="newMail border-primary"' : ''),
      // L'id
      $id,
      // L'auteur
      ($this->CopsMailJoint->getField('folderId')==2 ? 'Rédaction' : $auteur),
      // Sujet
      ($bln_NonLu ? '<strong>'.$sujet.'</strong>' : $sujet),
      // Pièce jointe ?
      ($bln_HasPjs ? '<i class="fa-solid fa-paperclip"></i>' : '&nbsp;'),
      // Date envoi
      $strSince,
      // action sur le mail
      ($this->CopsMailJoint->getField('folderId')==2 ? 'write' : 'read'),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
