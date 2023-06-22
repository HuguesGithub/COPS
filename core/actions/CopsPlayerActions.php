<?php
namespace core\actions;

use core\services\CopsPlayerServices;
use core\utils\SessionUtils;

/**
 * CopsPlayerActions
 * @author Hugues
 * @since v1.23.06.21
 * @version 1.23.06.25
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
     * @since v1.23.06.21
     * @version 1.23.06.25
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

        $id = $_POST['id'];
        $value = $_POST['value'];
        $field = substr($_POST['field'], 6);

        /*
        $id = SessionUtils::fromPost('id');
        $value = SessionUtils::fromPost('value');
        $field = substr(SessionUtils::fromPost('field'), 6);
        */

        $attributes[self::SQL_WHERE_FILTERS] = [self::FIELD_ID=>$id];
        $objCopsPlayerServices = new CopsPlayerServices();
        $objsCopsPlayer = $objCopsPlayerServices->getCopsPlayers($attributes);
        $objCopsPlayer = array_shift($objsCopsPlayer);

        if ($objCopsPlayer->getField(self::FIELD_ID)=='') {
            $returned = $this->getToastContentJson('danger', self::LABEL_ERREUR, vsprintf(self::DYN_WRONG_ID, [$id]));
        } elseif (in_array($field, $allowedFields)) {
            $objCopsPlayer->setField($field, $value);
            $objCopsPlayerServices->updatePlayer($objCopsPlayer);
            $returned = $this->getToastContentJson(
                'success',
                self::LABEL_SUCCES,
                vsprintf(self::DYN_SUCCESS_FIELD_UPDATE, [$field])
            );
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
