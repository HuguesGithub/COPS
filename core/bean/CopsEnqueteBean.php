<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEnqueteBean
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.16
 */
class CopsEnqueteBean extends LocalBean
{
  public function __construct($Obj=null)
  {
    $this->CopsEnquete = ($Obj==null ? new CopsEnquete() : $Obj);
  }

  /**
   * @since 1.22.09.16
   * @version 1.22.09.16
   */
  public function getEnqueteSaisie()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-enquete-saisie.php';

    $attributes = array(
      //
      '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }
  
  public function getCopsEnqueteRow()
  {
    $id			= $this->CopsEnquete->getField(self::FIELD_ID);
    $nomEnquete	= $this->CopsEnquete->getField(self::FIELD_NOM_ENQUETE);
	$intSince   = $this->CopsEnquete->getField(self::FIELD_DSTART);
	$intLast    = $this->CopsEnquete->getField(self::FIELD_DLAST);
	$strSince   = $this->displayNiceDateSince($intSince);
	$strLast    = $this->displayNiceDateSince($intLast);
	/*
    $auteur = $this->CopsMail->getField('user');
    $sujet = $this->CopsMail->getField('mail_subject');
    $bln_NonLu = ($this->CopsMail->getField('lu')==0);
    $bln_HasPjs = ($this->CopsMail->getField('nbPjs')!=0);
    // TODO
    $strSince = '5 mins ago';
	*/

    $strContent  = '<tr>';
    $strContent .= '<td><div class="icheck-primary"><input type="checkbox" value="'.$id.'" id="check'.$id.'"><label for="check'.$id.'"></label></div></td>';
//                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
    $strContent .= '<td class="mailbox-name"><a href="/admin?onglet=dossier&subOnglet=read&id='.$id.'" class="text-white"><strong>'.$nomEnquete.'</strong></a></td>';
	/*
    $strContent .= '<td class="mailbox-subject">'.($bln_NonLu ? '<strong>'.$sujet.'</strong>' : $sujet).'</td>';
    $strContent .= '<td class="mailbox-attachment">'.($bln_HasPjs ? '<i class="fa-solid fa-paperclip"></i>' : '&nbsp;').'</td>';
	*/
    $strContent .= '<td class="mailbox-date">'.$strSince.'</td>';
    $strContent .= '<td class="mailbox-date">'.$strLast.'</td>';
    $strContent .= '</tr>';
    return $strContent;
  }
  
  public function displayNiceDateSince($intDate)
  {
		$tsNow = UtilitiesBean::getCopsDate('tsnow');
	  $tsDiff = $tsNow-$intDate;
	  if ($tsDiff<60) {
		  return "À l'instant";
	  } elseif ($tsDiff<60*60) {
		  return "Il y a ".round($tsDiff/60)."min";
	  } elseif ($tsDiff<60*60*24) {
		  return "Il y a ".round($tsDiff/(60*60))."hrs";
	  } else {
		  return "Il y a ".round($tsDiff/(60*60*24))."j";
	  }
  }
}
