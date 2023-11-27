<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsMailJointBean
 * @author Hugues
 * @since 1.22.04.29
 * @version v1.23.12.02
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
   * @version v1.23.12.02
   */
  public function getInboxRow()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-inbox-row.php';

    $id           = $this->CopsMailJoint->getField(self::FIELD_ID);
    $CopsMail     = $this->CopsMailServices->getMail($this->CopsMailJoint->getField(self::FIELD_MAIL_ID));
    $CopsMailUser = $this->CopsMailServices->getMailUser($this->CopsMailJoint->getField(self::FIELD_FROM_ID));
    $auteur       = $CopsMailUser->getField(self::FIELD_USER);
    $sujet        = $CopsMail->getField(self::FIELD_MAIL_SUBJECT);
    $bln_NonLu    = ($this->CopsMailJoint->getField(self::FIELD_LU)==0);
    $bln_HasPjs   = ($this->CopsMailJoint->getField(self::FIELD_NB_PJS)!=0);
    $since        = $CopsMail->getField(self::FIELD_MAIL_DATE_ENVOI);
    // TODO
    $strSince = $since;//'5 mins ago';

    $attributes = [
        // Bordure gauche pour mettre en évidence les nouveaux mails
        ($bln_NonLu ? ' class="newMail border-primary"' : ''),
        // L'id
        $id,
        // L'auteur
        ($this->CopsMailJoint->getField(self::FIELD_FOLDER_ID)==2 ? 'Rédaction' : $auteur),
        // Sujet
        ($bln_NonLu ? '<strong>'.$sujet.'</strong>' : $sujet),
        // Pièce jointe ?
        ($bln_HasPjs ? '<i class="fa-solid fa-paperclip"></i>' : self::CST_NBSP),
        // Date envoi
        $strSince,
        // action sur le mail
        ($this->CopsMailJoint->getField(self::FIELD_FOLDER_ID)==2 ? 'write' : 'read'),
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

}
