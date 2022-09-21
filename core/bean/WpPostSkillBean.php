<?php
/**
 * WpPostSkillBean
 */
class WpPostSkillBean extends WpPostBean
{

  /**
   * Constructeur
   */
  public function __construct($objWpPost='')
  {
    $this->WpPost = $objWpPost;
  }

  public function getContentDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-library-skill.php';
    $rkSpecialisation = $this->WpPost->getPostMeta(self::WP_CF_SPECIALISATION);
    if ($rkSpecialisation>0) {
      $strSpecialisation = $rkSpecialisation.'+ ('.$this->WpPost->getPostMeta(self::WP_CF_LSTSPECS).')';
    } else {
      $strSpecialisation = 'Aucune';
    }

    $attributes = array(
      // Le nom de la compétence
      $this->WpPost->getField(self::WP_POSTTITLE),
      // Caractéristique associée
      $this->WpPost->getPostMeta(self::WP_CF_CARACASSOCIEE),
      // Niveau spécialisation
      $strSpecialisation,
      // Adrénaline
      ($this->WpPost->getPostMeta(self::WP_CF_ADRENALINE)==1 ? 'Oui' : 'Non'),
      // La description de la compétence et les exemples d'utilisation
      $this->WpPost->getField(self::WP_POSTCONTENT),
      '',//str_replace("\r\n", '<br>', $this->CopsSkill->getField(self::FIELD_SKILL_DESC)),
    );
    return $this->getRender($urlTemplate, $attributes);
  }

}
?>
