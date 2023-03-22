<?php
namespace core\bean;

use core\services\CopsSkillServices;

/**
 * WpPostSkillBean
 * @author Hugues
 * @since 1.22.00.00
 * @version 1.22.09.23
 */
class WpPostSkillBean extends WpPostBean
{
    /**
     * @return string
     */
    public function getContentDisplay()
    {
        ///////////////////////////////////////////////////////////////
        // On récupère les données de l'objet WordPress
        $postTitle = $this->WpPost->getField(self::WP_POSTTITLE);
        $postContent = $this->WpPost->getField(self::WP_POSTCONTENT);
        $rkSpecialisation = $this->WpPost->getPostMeta(self::WP_CF_SPECIALISATION);
        $padUsable = ($this->WpPost->getPostMeta(self::WP_CF_ADRENALINE)==1);
        $reference = $this->WpPost->getPostMeta('reference');
        $caracAssociee = $this->WpPost->getPostMeta(self::WP_CF_CARACASSOCIEE);
        ///////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////
        // On recherche l'entrée correspondante en base
        $objCopsSkillServices = new CopsSkillServices();
        $attributes = array(
            self::SQL_WHERE_FILTERS => array(
                self::FIELD_SKILL_ID => '%',
                self::FIELD_SKILL_NAME => $postTitle,
            ),
        );
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

            if ($rkSpecialisation!='' && $rkSpecialisation!=$objCopsSkill->getField(self::FIELD_SPEC_LEVEL)) {
                $objCopsSkill->setField(self::FIELD_SPEC_LEVEL, $rkSpecialisation);
                $blnUpdate = true;
            }

            if ($padUsable!='' && $padUsable!=$objCopsSkill->getField(self::FIELD_PAN_USABLE)) {
                $objCopsSkill->setField(self::FIELD_PAN_USABLE, $padUsable);
                $blnUpdate = true;
            }

            if ($reference!='' && $reference!=$objCopsSkill->getField(self::FIELD_REFERENCE)) {
                $objCopsSkill->setField(self::FIELD_REFERENCE, $reference);
                $blnUpdate = true;
            }

            if ($caracAssociee!='' && $caracAssociee!=$objCopsSkill->getField(self::FIELD_DEFAULT_ABILITY)) {
                $objCopsSkill->setField(self::FIELD_DEFAULT_ABILITY, $caracAssociee);
                $blnUpdate = true;
            }

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

        $attributes = array(
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
        );
        return $this->getRender($urlTemplate, $attributes);
    }

}

