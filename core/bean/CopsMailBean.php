<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsMailBean
 * @author Hugues
 * @since 1.22.04.29
 * @version 1.22.04.30
 */
class CopsMailBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsMail = ($Obj==null ? new CopsMail() : $Obj);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.30
   */
  public function getInboxRow()
  {
    $id = $this->CopsMail->getField(self::FIELD_ID);
    $auteur = $this->CopsMail->getField('user');
    $sujet = $this->CopsMail->getField('mail_subject');
    $bln_NonLu = ($this->CopsMail->getField('lu')==0);
    $bln_HasPjs = ($this->CopsMail->getField('nbPjs')!=0);
    // TODO
    $strSince = '5 mins ago';

    $strContent  = '<tr'.($bln_NonLu ? ' class="newMail border-primary"' : '').'>';
    $strContent .= '<td><div class="icheck-primary"><input type="checkbox" value="'.$id.'" id="check'.$id.'"><label for="check'.$id.'"></label></div></td>';
//                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
    $strContent .= '<td class="mailbox-name"><a href="/admin?onglet=inbox&subOnglet=read&id='.$id.'" class="text-white"><strong>'.$auteur.'</strong></a></td>';
    $strContent .= '<td class="mailbox-subject">'.($bln_NonLu ? '<strong>'.$sujet.'</strong>' : $sujet).'</td>';
    $strContent .= '<td class="mailbox-attachment">'.($bln_HasPjs ? '<i class="fa-solid fa-paperclip"></i>' : '&nbsp;').'</td>';
    $strContent .= '<td class="mailbox-date">'.$strSince.'</td>';
    $strContent .= '</tr>';
    return $strContent;
  }

}
