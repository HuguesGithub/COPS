<?php
namespace core\actions;

use core\domain\CopsCalGuyAddressClass;
use core\services\CopsCalAddressServices;
use core\services\CopsCalGuyAddressServices;
use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;

/**
 * CopsCalActions
 * @author Hugues
 * @since v1.23.12.02
 */
class CopsCalActions extends LocalActions
{

    /**
     * @since v1.23.10.12
     */
    public static function dealWithStatic(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        $objCopsCalActions = new CopsCalActions();
        return match ($ajaxAction) {
            self::AJAX_FIND_ADDRESS => $objCopsCalActions->findAddress(),
            self::AJAX_DEL_GUY_ADDRESS => $objCopsCalActions->deleteGuyAddress(),
            self::AJAX_INS_GUY_ADDRESS => $objCopsCalActions->insertGuyAddress(),
        };
    }

    /**
     * @since v1.23.10.12
     */
    public function findAddress(): string
    {
        $objServices = new CopsCalAddressServices();

        $streetDirection = SessionUtils::fromPost(self::FIELD_ST_DIRECTION);
        $streetName = SessionUtils::fromPost(self::FIELD_ST_NAME, false);
        $streetSuffix = SessionUtils::fromPost(self::FIELD_ST_SUFFIX);
        $streetSuffixDir = SessionUtils::fromPost(self::FIELD_ST_SUF_DIRECTION);
        $zipCode = SessionUtils::fromPost(self::FIELD_ZIPCODE);

        ///////////////////////////////////////////////////////
        if ($streetName == '') {
            $streetName = self::SQL_JOKER_SEARCH;
         } else {
            $streetName = self::SQL_JOKER_SEARCH.$streetName.self::SQL_JOKER_SEARCH;
         }
        $zipCode = $zipCode == '' ? self::SQL_JOKER_SEARCH : self::SQL_JOKER_SEARCH.$zipCode.self::SQL_JOKER_SEARCH;
        $sqlAttributes = [
            self::FIELD_ST_DIRECTION => $streetDirection == '' ? self::SQL_JOKER_SEARCH : $streetDirection,
            self::FIELD_ST_NAME => $streetName,
            self::FIELD_ST_SUFFIX => $streetSuffix == '' ? self::SQL_JOKER_SEARCH : $streetSuffix,
            self::FIELD_ST_SUF_DIRECTION => $streetSuffixDir == '' ? self::SQL_JOKER_SEARCH : $streetSuffixDir,
            self::FIELD_ZIPCODE => $zipCode,
        ];
        $objs = $objServices->getCalAddresses($sqlAttributes);

        $arrStDir = [];
        $arrStName = [];
        $arrStSuf = [];
        $arrStSufDir = [];
        $arrZipCode = [];
        foreach ($objs as $obj) {
            $value = $obj->getField(self::FIELD_ST_DIRECTION);
            if (!isset($arrStDir[$value])) {
                $arrStDir[$value] = $obj->getBean()->getDropDownLi(self::FIELD_ST_DIRECTION);
            }
            $value = $obj->getField(self::FIELD_ST_NAME);
            if (!isset($arrStName[$value])) {
                $arrStName[$value] = $obj->getBean()->getDropDownLi(self::FIELD_ST_NAME);
            }
            $value = $obj->getField(self::FIELD_ST_SUFFIX);
            if (!isset($arrStSuf[$value])) {
                $arrStSuf[$value] = $obj->getBean()->getDropDownLi(self::FIELD_ST_SUFFIX);
            }
            $value = $obj->getField(self::FIELD_ST_SUF_DIRECTION);
            if (!isset($arrStSufDir[$value])) {
                $arrStSufDir[$value] = $obj->getBean()->getDropDownLi(self::FIELD_ST_SUF_DIRECTION);
            }
            $value = $obj->getField(self::FIELD_ZIPCODE);
            if (!isset($arrZipCode[$value])) {
                $arrZipCode[$value] = $obj->getBean()->getDropDownLi(self::FIELD_ZIPCODE);
            }
        }

        $strJTOE = JSON_THROW_ON_ERROR;

        $strReturned  = '{';
        $strReturned .= '"dropDownstreetDirection": '.json_encode(implode('', $arrStDir), $strJTOE);
        $strReturned .= ',"dropDownstreetName": '.json_encode(implode('', $arrStName), $strJTOE);
        $strReturned .= ',"dropDownstreetSuffix": '.json_encode(implode('', $arrStSuf), $strJTOE);
        $strReturned .= ',"dropDownstreetSuffixDirection": '.json_encode(implode('', $arrStSufDir), $strJTOE);
        $strReturned .= ',"dropDownzipCode": '.json_encode(implode('', $arrZipCode), $strJTOE);
        $strReturned .= '}';
        return $strReturned;
    }

    /**
     * @since v1.23.12.02
     */
    public function deleteGuyAddress()
    {
        $objServices = new CopsCalGuyAddressServices();
        $id = SessionUtils::fromPost(self::FIELD_ID);

        $objs = $objServices->getCalGuyAddresses([self::FIELD_ID=>$id]);
        if (count($objs)==1) {
            $obj = array_shift($objs);
            $objServices->deleteCalGuyAddress($obj);
            $msg = "La suppression de l'adresse s'est correctement déroulée.";
        } else {
            $msg = 'Une erreur est survenue lors de la suppression de cette adresse.';
        }
        return $this->getToastContentJson(self::NOTIF_INFO, 'Suppression adresse', $msg);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertGuyAddress()
    {
        $objAddressServices = new CopsCalAddressServices();
        $objGuyAddressServices = new CopsCalGuyAddressServices();

        $attributes = [
            self::FIELD_ST_DIRECTION => SessionUtils::fromPost(self::FIELD_ST_DIRECTION),
            self::FIELD_ST_NAME => SessionUtils::fromPost(self::FIELD_ST_NAME, false),
            self::FIELD_ST_SUFFIX => SessionUtils::fromPost(self::FIELD_ST_SUFFIX),
            self::FIELD_ST_SUF_DIRECTION => SessionUtils::fromPost(self::FIELD_ST_SUF_DIRECTION),
            self::FIELD_ZIPCODE => SessionUtils::fromPost(self::FIELD_ZIPCODE),
        ];
        $objs = $objAddressServices->getCalAddresses($attributes);
        if (!empty($objs)) {
            // On vérifie que les données saisies correspondent à une entrée existante.
            // On peut alors sauvegarder dans CalGuyAddress
            $obj = array_shift($objs);

            $attributes = [
                self::FIELD_NUMBER => SessionUtils::fromPost(self::FIELD_NUMBER),
                self::FIELD_GUY_ID => SessionUtils::fromPost(self::FIELD_GUY_ID),
                self::FIELD_ADDRESS_ID => $obj->getField(self::FIELD_ID)
            ];
            $objCalGuyAddress = new CopsCalGuyAddressClass($attributes);
            $objGuyAddressServices->insertCalGuyAddress($objCalGuyAddress);
            $msg = "Création de l'adresse pour cette personne réussie.";
        } else {
            $msg = "Création impossible. Problème de données.";
        }

        return $this->getToastContentJson(self::NOTIF_INFO, 'Création adresse', $msg);
    }
}
