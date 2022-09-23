<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe CopsMailFolderBean
 * @author Hugues
 * @since 1.22.05.02
 * @version 1.22.05.02
 */
class CopsMailFolderBean extends UtilitiesBean
{
  /**
   * Class constructor
   * @since 1.22.05.02
   * @version 1.22.05.02
   */
  public function __construct($Obj=null)
  {
    $this->CopsMailFolder = ($Obj==null ? new CopsMailFolder() : $Obj);
  }

  /**
   * @since 1.22.05.02
   * @version 1.22.05.02
   */
  public function getMenuFolder($activeSlug='')
  {
    $slug = $this->CopsMailFolder->getField(self::FIELD_SLUG);
    $nbMailsNonLus = $this->CopsMailFolder->getNombreMailsNonLus(array(self::FIELD_SLUG=>$slug));
    if ($nbMailsNonLus!=0) {
      $strBadge = $this->getBalise(self::TAG_SPAN, $nbMailsNonLus, array(self::ATTR_CLASS=>'badge bg-primary float-right'));
    } else {
      $strBadge = '';
    }

    $urlTemplate = 'web/pages/public/fragments/public-fragments-li-menu-folder.php';
    $attributes = array(
      // Menu sélectionné ou pas ?
      ($slug==$activeSlug ? ' '.self::CST_ACTIVE : ''),
      // L'url du folder
      '/admin?onglet=inbox&amp;subOnglet='.$slug,
      // L'icône
      $this->CopsMailFolder->getField(self::FIELD_ICON),
      // Le libellé + l'éventuel badge
      $this->CopsMailFolder->getField(self::FIELD_LABEL).$strBadge,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
