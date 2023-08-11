<?php
namespace core\actions;

use core\services\CopsPlayerServices;
use core\utils\SessionUtils;

/**
 * CopsPlayerActions
 * @author Hugues
 * @since v1.23.06.21
 * @version v1.23.08.12
 */
class CopsPlayerActions extends LocalActions
{
    /**
     * @since v1.23.06.21
     * @version 1.23.06.25
     */
    public static function dealWithStatic(): string
    {
        $ajaxAction = $_POST[self::AJAX_ACTION];
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
            self::FIELD_BACKGROUND, self::FIELD_PX_CUR,
        ];

        $id = SessionUtils::fromPost('id');
        $value = SessionUtils::fromPost('value');
        $field = substr(SessionUtils::fromPost('field'), 6);

        $attributes = [self::FIELD_ID=>$id];
        $objCopsPlayerServices = new CopsPlayerServices();
        $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
        $objCopsPlayer = array_shift($objsCopsPlayer);

        if ($objCopsPlayer->getField(self::FIELD_ID)=='') {
            $returned = $this->getToastContentJson('danger', self::LABEL_ERREUR, vsprintf(self::DYN_WRONG_ID, [$id]));
        } elseif (in_array($field, $allowedFields)) {
            if ($objCopsPlayer->checkField($field, $value)) {
                $arrToast = [];

                $objCopsPlayer->setField($field, $value);
                // Selon le statut du personnage, on retourne éventuellement une info.
                if ($objCopsPlayer->getField(self::FIELD_STATUS)==self::PS_CREATE_1ST_STEP) {
                    // On est dans le cas spécifique de la création d'un personnage.
                    // On est durant la première étape : saisie du nom, du prénom et des caractéristiques
                    $strStatus = '';
                    if ($objCopsPlayer->isOverStep(1, $strStatus)) {
                        // Si les champs attendus sont renseignés, on passe à l'étape suivante
                        $objCopsPlayer->validFirstCreationStep();
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
                $objCopsPlayerServices->updatePlayer($objCopsPlayer);

                $strUpdate = $this->getToastContent(
                    'success',
                    self::LABEL_SUCCES,
                    vsprintf(self::DYN_SUCCESS_FIELD_UPDATE, [$field])
                );
                array_push($arrToast, $strUpdate);
                $returned = '{"toastContent": '.json_encode($arrToast).'}';
            } else {
                $returned = $this->getToastContentJson(
                    'warning',
                    self::LABEL_ERREUR,
                    vsprintf(self::DYN_WRONG_VALUE, [$value, $field])
                );
            }
        } else {
            $returned = $this->getToastContentJson(
                'warning',
                self::LABEL_ERREUR,
                vsprintf(self::DYN_WRONG_FIELD, [$field])
            );
        }

        return $returned;
    }

}
