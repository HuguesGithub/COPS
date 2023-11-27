<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsMailBean
 * @author Hugues
 * @since 1.22.04.29
 * @version v1.23.12.02
 */
class CopsMailBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsMail = ($Obj==null ? new CopsMail() : $Obj);
  }

  /**
   * @since 1.22.04.29
   * @version v1.23.12.02
   */
  public function getInboxRow()
  {
    $id = $this->CopsMail->getField(self::FIELD_ID);
    $auteur = $this->CopsMail->getField(self::FIELD_USER);
    $sujet = $this->CopsMail->getField(self::FIELD_MAIL_SUBJECT);
    $bln_NonLu = ($this->CopsMail->getField(self::FIELD_LU)==0);
    $bln_HasPjs = ($this->CopsMail->getField(self::FIELD_NB_PJS)!=0);
    // TODO
    $strSince = '5 mins ago';

    $strContent  = '<tr'.($bln_NonLu ? ' class="newMail border-primary"' : '').'>';
    $strContent .= '<td><div class="icheck-primary"><input type="checkbox" value="'.$id.'" id="check'.$id;
    $strContent .= '"><label for="check'.$id.'"></label></div></td>';
//                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
    $strContent .= '<td class="mailbox-name"><a href="/admin?onglet=inbox&subOnglet=read&id='.$id;
    $strContent .= '" class="text-white"><strong>'.$auteur.'</strong></a></td>';
    $strContent .= '<td class="mailbox-subject">'.($bln_NonLu ? '<strong>'.$sujet.'</strong>' : $sujet).'</td>';
    $strContent .= '<td class="mailbox-attachment">';
    $strContent .= ($bln_HasPjs ? '<i class="fa-solid fa-paperclip"></i>' : self::CST_NBSP).'</td>';
    $strContent .= '<td class="mailbox-date">'.$strSince.'</td>';
    $strContent .= '</tr>';
    return $strContent;
  }

}
