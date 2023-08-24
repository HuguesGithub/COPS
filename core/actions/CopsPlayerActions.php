<?php
namespace core\actions;

use core\services\CopsPlayerServices;
use core\services\CopsSkillServices;
use core\utils\SessionUtils;

/**
 * CopsPlayerActions
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.12
 */
class CopsPlayerActions extends LocalActions
{
    public $objCopsPlayerServices;
    public $objSkillServices;
    public $objCopsPlayer;

    /**
     * @since v1.23.06.21
     * @version 1.23.06.25
     */
    public static function dealWithStatic(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        $objCopsPlayerActions = new CopsPlayerActions();
        return match ($ajaxAction) {
            'saveData' => $objCopsPlayerActions->updateCopsPlayer(),
            //default => static::getErrorActionContent($ajaxAction),
        };
    }

    /**
     * @since vv1.23.06.21
     * @version v1.23.08.12
     */
    public function updateCopsPlayer(): string
    {
        // On défini les champs autorisés à la mise à jour
        $allowedFields = [
            self::FIELD_BIRTH_DATE, self::FIELD_NOM, self::FIELD_PRENOM, self::FIELD_SURNOM, self::FIELD_CARAC_CARRURE,
            self::FIELD_CARAC_CHARME, self::FIELD_CARAC_COORDINATION, self::FIELD_CARAC_EDUCATION,
            self::FIELD_CARAC_PERCEPTION, self::FIELD_CARAC_REFLEXES, self::FIELD_CARAC_SANGFROID, self::FIELD_PV_CUR,
            self::FIELD_PAD_CUR, self::FIELD_PAN_CUR, self::FIELD_TAILLE, self::FIELD_POIDS, self::FIELD_GRADE,
            self::FIELD_GRADE_RANG, self::FIELD_GRADE_ECHELON, self::FIELD_INTEGRATION_DATE, self::FIELD_SECTION,
            self::FIELD_BACKGROUND, self::FIELD_PX_CUR, self::FIELD_CHEVEUX, self::FIELD_YEUX, self::FIELD_SEXE,
            self::FIELD_ETHNIE,
        ];
        $allowedSkills = [
            'langue',
        ];

        $id = SessionUtils::fromPost('id');
        $value = SessionUtils::fromPost('value');
        $field = substr(SessionUtils::fromPost('field'), 6);

        $attributes = [self::FIELD_ID=>$id];
        $this->objCopsPlayerServices = new CopsPlayerServices();
        $objsCopsPlayer = $this->objCopsPlayerServices->getCopsPlayers($attributes);
        $this->objCopsPlayer = array_shift($objsCopsPlayer);

        if ($this->objCopsPlayer->getField(self::FIELD_ID)=='') {
            $returned = $this->getToastContentJson('danger', self::LABEL_ERREUR, vsprintf(self::DYN_WRONG_ID, [$id]));
        } elseif (in_array($field, $allowedFields)) {
            $this->dealWithUpdateAbility($field, $value);
        } elseif (in_array($field, $allowedSkills)) {
            $this->objSkillServices = new CopsSkillServices();
            $objPlayerSkill = $this->objSkillServices->getPlayerSkill($id);
            $objCopsPlayer = $objPlayerSkill->getPlayer();
            if ($field=='langue') {
                $this->dealWithLangue($objCopsPlayer, $id);
            }
            // On aura ensuite les différentes compétences qui peuvent être mises à jour.
            // Durant le processus de création (2è étape), on ne peut pas les mettre à jour
            // tant que les langues n'ont pas été choisies et les compétences à éliminer, éliminées
            // (2 parmi Rhétorique, Intimidation, Eloquence et 2 parmi Coups, Projections et Immobilisations)

            // On doit ensuite regarder combien de points de compétence ont été dépensés, parmi les 10 possibles.
            // Prévoir de pouvoir ajouter de nouvelles compétences.

            // On gère les modifications des compétences.
        } else {
            $returned = $this->getToastContentJson(
                'warning',
                self::LABEL_ERREUR,
                vsprintf(self::DYN_WRONG_FIELD, [$field])
            );
        }

        return $returned;
    }

    /**
     * @since v1.23.08.19
     */
    public function updateAbility(string $field, mixed $value): string
    {
        $arrToast = [];

        $this->objCopsPlayer->setField($field, $value);
        // Selon le statut du personnage, on retourne éventuellement une info.
        if ($this->objCopsPlayer->getField(self::FIELD_STATUS)==self::PS_CREATE_1ST_STEP) {
            // On est dans le cas spécifique de la création d'un personnage.
            // On est durant la première étape : saisie du nom, du prénom et des caractéristiques
            $strStatus = '';
            if ($this->objCopsPlayer->isOverStep(1, $strStatus)) {
                // Si les champs attendus sont renseignés, on passe à l'étape suivante
                $this->objCopsPlayer->validFirstCreationStep();
                $strInfo = $this->getToastContent(
                    'info',
                    'Information',
                    'La première étape de création du personnage est terminée.'
                );
            } else {
                $strInfo = $this->getToastContent('info', 'Information', $strStatus);
            }
            array_push($arrToast, $strInfo);
        }
        $this->objCopsPlayerServices->updatePlayer($this->objCopsPlayer);

        $strUpdate = $this->getToastContent(
            'success',
            self::LABEL_SUCCES,
            vsprintf(self::DYN_SUCCESS_FIELD_UPDATE, [$field])
        );
        array_push($arrToast, $strUpdate);
        return '{"toastContent": '.json_encode($arrToast).'}';
    }

    private function dealWithUpdateAbility(string $field, mixed $value): string
    {
        if ($this->objCopsPlayer->checkField($field, $value)) {
            $returned = $this->updateAbility($field, $value);
        } else {
            $returned = $this->getToastContentJson(
                'warning',
                self::LABEL_ERREUR,
                vsprintf(self::DYN_WRONG_VALUE, [$value, $field])
            );
        }
        return $returned;
    }

    private function dealWithLangue(CopsPlayerClass $objCopsPlayer, int $id): string
    {
        $objsPlayerSkill = $objCopsPlayer->getCopsSkills();
        $areAllLanguesOk = true;
        $areLanguesUniques = true;
        $arrLangues = [];
        while (!empty($objsPlayerSkill)) {
            $objPlayerSkill = array_shift($objsPlayerSkill);
            if ($objPlayerSkill->getField(self::FIELD_SKILL_ID)==34) {
                continue;
            }

            if ($objPlayerSkill->getField(self::FIELD_SPEC_SKILL_ID)==0 &&
                $objPlayerSkill->getField(self::FIELD_ID)!=$id) {
                $areAllLanguesOk = false;
            }
            if (!isset($arrLangues[$objPlayerSkill->getField(self::FIELD_SPEC_SKILL_ID)])) {
                array_push($arrLangues, $objPlayerSkill->getField(self::FIELD_SPEC_SKILL_ID));
            } else {
                $areLanguesUniques = false;
            }
        }

        if ($areLanguesUniques && $areAllLanguesOk) {
            $objPlayerSkill = $this->objSkillServices->getPlayerSkill($id);
            $objPlayerSkill->setField(self::FIELD_SPEC_SKILL_ID, $value);
            $this->objSkillServices->updatePlayerSkill($objPlayerSkill);
            $returned = $this->getToastContentJson(
                'success',
                self::LABEL_SUCCES,
                'Compétence mise à jour.'
            );
        } else {
            $returned = $this->getToastContentJson(
                'warning',
                self::LABEL_ERREUR,
                'Langue en double ou langue non sélectionnée.',
            );
                // TODO : gestion de l'erreur.
        }
        return $returned;
    }
}
