<?php
namespace core\bean;

use core\services\CopsSkillServices;

/**
 * WpPostSkillBean
 * @author Hugues
 * @since 1.22.00.00
 * @version v1.23.08.12
 */
class WpPostSkillBean extends WpPostBean
{
    /**
     * @return string
     * @version v1.23.08.12
     */
    public function getContentDisplay()
    {
        ///////////////////////////////////////////////////////////////
        // On récupère les données de l'objet WordPress
        $postTitle = $this->WpPost->getField(self::WP_POSTTITLE);
        $postContent = $this->WpPost->getField(self::WP_POSTCONTENT);
        $rkSpecialisation = $this->WpPost->getPostMeta(self::WP_CF_SPECIALISATION);
        $padUsable = ($this->WpPost->getPostMeta(self::WP_CF_ADRENALINE)==1);
        $reference = $this->WpPost->getPostMeta(self::FIELD_REFERENCE);
        $caracAssociee = $this->WpPost->getPostMeta(self::WP_CF_CARACASSOCIEE);
        ///////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////
        // On recherche l'entrée correspondante en base
        $objCopsSkillServices = new CopsSkillServices();
        $attributes = [self::FIELD_SKILL_ID => '%', self::FIELD_SKILL_NAME => $postTitle];
        $objsCopsSkill = $objCopsSkillServices->getSkills($attributes);
        ///////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////
        // Si on a une occurrence, on met à jour l'entrée si nécessaire.
        if (count($objsCopsSkill)==1) {
            $objCopsSkill = array_shift($objsCopsSkill);
            $this->backUp($objCopsSkill);
            $blnUpdate = false;
            // TODO : Le content et les usages sont dans le content du Post. A gérer.
            // $postContent
            // $objCopsSkill->getField(self::FIELD_SKILL_DESC)
            // $objCopsSkill->getField(self::FIELD_SKILL_USES)

            $this->updateField($objCopsSkill, self::FIELD_SPEC_LEVEL, $rkSpecialisation, $blnUpdate);
            $this->updateField($objCopsSkill, self::FIELD_PAD_USABLE, $padUsable, $blnUpdate);
            $this->updateField($objCopsSkill, self::FIELD_REFERENCE, $reference, $blnUpdate);
            $this->updateField($objCopsSkill, self::FIELD_DEFAULT_ABILITY, $caracAssociee, $blnUpdate);

            if ($blnUpdate) {
                $objCopsSkillServices->update($objCopsSkill);
            }
        }

        $urlTemplate = self::WEB_PPFA_LIB_SKILL;
        if ($rkSpecialisation>0) {
            $strSpecialisation = $rkSpecialisation.'+ ('.$this->WpPost->getPostMeta(self::WP_CF_LSTSPECS).')';
        } else {
            $strSpecialisation = 'Aucune';
        }

        $attributes = [
            // Le nom de la compétence
            $postTitle,
            // Caractéristique associée
            $caracAssociee,
            // Niveau spécialisation
            $strSpecialisation,
            // Adrénaline
            ($padUsable ? 'Oui' : 'Non'),
            // La description de la compétence et les exemples d'utilisation
            $postContent,
            //str_replace("\r\n", '<br>', $this->CopsSkill->getField(self::FIELD_SKILL_DESC)),
            '',
        ];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @since v1.23.06.05
     * @version v1.23.06.11
     */
    public function updateField($objCopsSkill, string $field, $value, &$blnUpdate): void
    {
        if ($value!='' && $value!=$objCopsSkill->getField(field)) {
            $objCopsSkill->setField(field, $value);
            $blnUpdate = true;
        }
    }
}

