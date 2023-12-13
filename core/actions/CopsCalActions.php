<?php
namespace core\actions;

use core\domain\CopsCalGuyAddressClass;
use core\domain\CopsCalGuyPhoneClass;
use core\services\CopsCalAddressServices;
use core\services\CopsCalPhoneServices;
use core\services\CopsCalGuyAddressServices;
use core\services\CopsCalGuyPhoneServices;
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
            self::AJAX_FIND_ADDRESS    => $objCopsCalActions->findAddress(),
            self::AJAX_FIND_PHONE      => $objCopsCalActions->findPhone(),
            self::AJAX_DEL_GUY_ADDRESS => $objCopsCalActions->deleteGuyAddress(),
            self::AJAX_DEL_GUY_PHONE   => $objCopsCalActions->deleteGuyPhone(),
            self::AJAX_INS_GUY_ADDRESS => $objCopsCalActions->insertGuyAddress(),
            self::AJAX_INS_GUY_PHONE   => $objCopsCalActions->insertGuyPhone(),
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

    /**
     * @since v1.23.12.02
     */
    public function findPhone(): string
    {
        $objServices = new CopsCalPhoneServices();

        $cityName = SessionUtils::fromPost(self::FIELD_CITY_NAME, false);
        $phoneNumberFirst = SessionUtils::fromPost(self::CST_PN_FIRST);
        $phoneNumberSecond = SessionUtils::fromPost(self::CST_PN_SECOND);

        ///////////////////////////////////////////////////////
        if ($cityName == '') {
            $cityName = self::SQL_JOKER_SEARCH;
        } else {
            $cityName = self::SQL_JOKER_SEARCH.$cityName.self::SQL_JOKER_SEARCH;
        }
        $phoneNumberFirst .= self::SQL_JOKER_SEARCH;
        $phoneNumberSecond .= self::SQL_JOKER_SEARCH;
        $phoneNumber = $phoneNumberFirst.'-'.$phoneNumberSecond;

        $sqlAttributes = [
            self::FIELD_PHONE_ID => $phoneNumber,
            self::FIELD_CITY_NAME => $cityName,
        ];
        $objs = $objServices->getCalPhones($sqlAttributes);

        $arrCityName = [];
        $arrPnFirst = [];
        $arrPnSecond = [];
        foreach ($objs as $obj) {
            $value = $obj->getField(self::FIELD_CITY_NAME);
            if (!isset($arrCityName[$value])) {
                $arrCityName[$value] = $obj->getBean()->getDropDownLi(self::FIELD_CITY_NAME);
            }
            $value = $obj->getField(self::FIELD_PHONE_ID);
            list($first, $second) = explode('-', $value);
            if (!isset($arrPnFirst[$first])) {
                $arrPnFirst[$first] = $obj->getBean()->getDropDownLi(self::CST_PN_FIRST);
            }
            if (!isset($arrPnSecond[$second])) {
                $arrPnSecond[$second] = $obj->getBean()->getDropDownLi(self::CST_PN_SECOND);
            }
        }

        $strJTOE = JSON_THROW_ON_ERROR;

        $strReturned  = '{';
        $strReturned .= '"dropDowncityName": '.json_encode(implode('', $arrCityName), $strJTOE);
        $strReturned .= ',"dropDownphoneNumberFirst": '.json_encode(implode('', $arrPnFirst), $strJTOE);
        $strReturned .= ',"dropDownphoneNumberSecond": '.json_encode(implode('', $arrPnSecond), $strJTOE);
        $strReturned .= '}';
        return $strReturned;
    }

    /**
     * @since v1.23.12.02
     */
    public function deleteGuyPhone()
    {
        $objServices = new CopsCalGuyPhoneServices();
        $id = SessionUtils::fromPost(self::FIELD_ID);

        $objs = $objServices->getCalGuyPhones([self::FIELD_ID=>$id]);
        if (count($objs)==1) {
            $obj = array_shift($objs);
            $objServices->deleteCalGuyPhone($obj);
            $msg = "La suppression du téléphone s'est correctement déroulée.";
        } else {
            $msg = 'Une erreur est survenue lors de la suppression de ce téléphone.';
        }
        return $this->getToastContentJson(self::NOTIF_INFO, 'Suppression téléphone', $msg);
    }

    /**
     * @since v1.23.12.02
     */
    public function insertGuyPhone()
    {
        $objPhoneServices = new CopsCalPhoneServices();
        $objGuyPhoneServices = new CopsCalGuyPhoneServices();

        $cityName = SessionUtils::fromPost(self::FIELD_CITY_NAME, false);
        $phoneNumberFirst = SessionUtils::fromPost(self::CST_PN_FIRST);
        $phoneNumberSecond = SessionUtils::fromPost(self::CST_PN_SECOND);
        $phoneNumberThird = SessionUtils::fromPost(self::CST_PN_THIRD);
        $guyId = SessionUtils::fromPost(self::FIELD_GUY_ID);

        $attributes = [];
        $msgErr = '';
        if ($this->checkRequired($phoneNumberFirst, $phoneNumberSecond, $cityName, $attributes, $msgErr)) {
            $phoneRacine = $phoneNumberFirst.'-'.$phoneNumberSecond;
            // On a des critères de recherche valables. On va trouver un élément qui y correspond.
            $objs = $objPhoneServices->getCalPhones($attributes);
            if (empty($objs)) {
                // On n'a pas trouvé d'équivalence. Les données ne sont pas bonnes
                $msgErr = "Erreur lors de la vérification des données.";
            } else {
                // Si celle sélectionné a des • dans le phoneId, on les remplace par des chiffres aléatoires.
                $pos = strpos($phoneRacine, '•');
                switch ($pos) {
                    case '6' :
                        $phoneRacine = substr($phoneRacine, 0, 5).rand(0, 9);
                    break;
                    case '5' :
                        $phoneRacine = substr($phoneRacine, 0, 4).str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                    break;
                    case '4' :
                        $phoneRacine = substr($phoneRacine, 0, 3).str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                    break;
                    default :
                    break;
                }

                // En théorie, à ce stade, on a un xxx-xxx valide.
                // On va donc ajouter le troisième terme. Vérifier qu'il n'est pas déjà présent et l'enregistrer
                if ($phoneNumberThird=='') {
                    $phoneNumberThird = str_pad(rand(0, 999), 4, '0', STR_PAD_LEFT);
                }
                $tries = 0;
                do {
                    $attributes = [
                        self::FIELD_GUY_ID => $guyId,
                        self::FIELD_PHONENUMBER => $phoneRacine.'-'.$phoneNumberThird
                    ];
                    $objs = $objGuyPhoneServices->getCalGuyPhones($attributes);
                    $phoneNumberThird = str_pad(rand(0, 999), 4, '0', STR_PAD_LEFT);
                    $tries++;
                } while (!empty($objs) && $tries<50);

                if ($tries>=50) {
                    $msgErr = 'Trop de tentatives pour trouver un numéro valide.';
                } else {
                    $objCalGuyPhone = new CopsCalGuyPhoneClass($attributes);
                    $objGuyPhoneServices->insertCalGuyPhone($objCalGuyPhone);
                }
            }
        }
        if ($msgErr!='') {
            return $this->getToastContentJson(self::NOTIF_WARNING, 'Création téléphone', 'Erreur : '.$msgErr);
        } else {
            return $this->getToastContentJson(self::NOTIF_INFO, 'Création téléphone', 'Création réussie');
        }
    }

    /**
     * @since v1.23.12.02
     */
    private function checkRequired(
        string $phoneNumberFirst,
        string $phoneNumberSecond,
        string $cityName,
        array &$attributes,
        string &$msgErr
    ): bool
    {
        $blnOk = true;
        if ($phoneNumberFirst=='') {
            if ($cityName=='') {
                $blnOk = false;
                $msgErr = "Erreur si le premier indicatif n'est pas renseigné, le nom de la ville doit l'être.";
            } else {
                $attributes = [self::FIELD_CITY_NAME=>$cityName];
            }
        } else {
            if ($phoneNumberSecond!='') {
                $attributes = [self::FIELD_PHONE_ID=>$phoneNumberFirst.'-'.$phoneNumberSecond];
            } elseif ($cityName!='') {
                $attributes = [self::FIELD_PHONE_ID=>$phoneNumberFirst, self::FIELD_CITY_NAME=>$cityName];
            } else {
                $blnOk = false;
                $msgErr  = "Erreur si le premier indicatif est renseigné, ";
                $msgErr .= "le deuxième indicatif ou le nom de la ville doivent l'être également.";
            }
        }
        return $blnOk;
    }
}
