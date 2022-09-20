<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsSkillBean
 * @author Hugues
 * @since 1.22.05.30
 * @version 1.22.05.30
 */
class CopsSkillBean extends LocalBean
{
  public function __construct($Obj=null)
  {
    $this->CopsSkill = ($Obj==null ? new CopsSkill() : $Obj);
  }

  /**
   * @since 1.22.05.30
   * @version 1.22.05.30
   */
  public function getLibraryDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-library-skill.php';

    if ($this->CopsSkill->getField(self::FIELD_SPEC_LEVEL)>0) {
      $strSpecialisation = $this->CopsSkill->getField(self::FIELD_SPEC_LEVEL).'+ (';
      $SkillSpecs = $this->CopsSkill->getSpecialisations();

      while (!empty($SkillSpecs)) {
        $SkillSpec = array_shift($SkillSpecs);
        $strSpecialisation .= $SkillSpec->getField(self::FIELD_SPEC_NAME).', ';
      }
      $strSpecialisation = substr($strSpecialisation, 0, -2).')';
    } else {
      $strSpecialisation = 'Aucune';
    }

    $attributes = array(
      // Le nom de la compétence
      $this->CopsSkill->getField(self::FIELD_SKILL_NAME),
      // Caractéristique associée
      $this->CopsSkill->getField(self::FIELD_DEFAULT_ABILITY),
      // Niveau spécialisation
      $strSpecialisation,
      // Adrénaline
      ($this->CopsSkill->getField(self::FIELD_PAD_USABLE)==1?'Oui':'Non'),
      // La description de la compétence
      str_replace("\r\n", '<br>', $this->CopsSkill->getField(self::FIELD_SKILL_DESC)),
      // Exemples d'utilisation
      str_replace("\r\n", '<br>', $this->CopsSkill->getField(self::FIELD_SKILL_USES)),
    );
    return $this->getRender($urlTemplate, $attributes);
  }
}
