<?php
namespace core\actions;

use core\services\CopsRandomGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;

/**
 * CopsCalActions
 * @author Hugues
 * @since v1.23.10.12
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
            'findAddress' => $objCopsCalActions->findAddress(),
        };
    }

    /**
     * @since v1.23.10.12
     */
    public function findAddress(): string
    {
        $objServices = new CopsRandomGuyServices();

        $phoneNumber = substr(SessionUtils::fromPost('phoneNumber'), 0, 7);
        $zipCode = SessionUtils::fromPost('zipCode');
        $city = SessionUtils::fromPost('city', false);

        ///////////////////////////////////////////////////////
        $sqlAttributes = [
            self::FIELD_PHONE_ID  => self::SQL_JOKER_SEARCH.$phoneNumber.self::SQL_JOKER_SEARCH,
            self::FIELD_ZIP       => self::SQL_JOKER_SEARCH.$zipCode.self::SQL_JOKER_SEARCH,
            self::FIELD_CITY_NAME => self::SQL_JOKER_SEARCH.$city.self::SQL_JOKER_SEARCH,
        ];

        $liAttributes = [
            self::ATTR_CLASS => 'zoomTitreCol',
            self::ATTR_STYLE => 'width: 80px;',
        ];
        $strContentHeader  = HtmlUtils::getBalise(self::TAG_LI, self::LABEL_PHONE, $liAttributes);
        $strContentHeader .= HtmlUtils::getBalise(self::TAG_LI, self::LABEL_ZIPCODE, $liAttributes);
        $liAttributes[self::ATTR_STYLE] = 'width: 160px;';
        $strContentHeader .= HtmlUtils::getBalise(self::TAG_LI, 'Ville', $liAttributes);

        $strHeader = HtmlUtils::getBalise(
            self::TAG_UL,
            $strContentHeader,
            [
                self::ATTR_CLASS => 'zoomTitresCol ui-menu ui-autocomplete',
                self::ATTR_STYLE => 'width: 325px;',
            ]
        );

        $content = '';
        $arrs = $objServices->getTripletAdresse($sqlAttributes);
        if (empty($arrs)) {
            return '{"refresh": '.json_encode('', JSON_THROW_ON_ERROR).'}';
        }
        if (count($arrs)>100) {
            $blnTropReponses = true;
            array_pop($arrs);
        } else {
            $blnTropReponses = false;
        }
        while (!empty($arrs)) {
            $arr = array_shift($arrs);
            $liAttributes[self::ATTR_STYLE] = 'width: 80px;';
            $liAttributes[self::ATTR_DATA][self::ATTR_DATA_TARGET] = 'telephoneNumber';
            $contentLink  = HtmlUtils::getBalise(self::TAG_SPAN, $arr->phoneId, $liAttributes);
            $liAttributes[self::ATTR_DATA][self::ATTR_DATA_TARGET] = 'zipCode';
            $contentLink .= HtmlUtils::getBalise(self::TAG_SPAN, $arr->zip, $liAttributes);
            $liAttributes[self::ATTR_STYLE] = 'width: 160px;';
            $liAttributes[self::ATTR_DATA][self::ATTR_DATA_TARGET] = 'city';
            $contentLink .= HtmlUtils::getBalise(self::TAG_SPAN, $arr->cityName, $liAttributes);

            $contentLi = HtmlUtils::getLink($contentLink, '#', 'pb-0 pt-0 ui-menu-item-wrapper');
            $content .= HtmlUtils::getBalise(
                self::TAG_LI,
                $contentLi,
                [self::ATTR_CLASS=>'list-group-item m-0 p-0 ui-menu-item']
            );
        }
        if ($blnTropReponses) {
            $contentLink = HtmlUtils::getBalise(
                self::TAG_SPAN,
                'Plus de 100 possibilités.',
                [self::ATTR_CLASS=>'zoomCellule', self::ATTR_STYLE=>'width: 320px;']
            );
            $contentLi = HtmlUtils::getLink($contentLink, '#', 'pb-0 pt-0 ui-menu-item-wrapper');
            $content .= HtmlUtils::getBalise(
                self::TAG_LI,
                $contentLi,
                [self::ATTR_CLASS=>'list-group-item m-0 p-0 ui-menu-item']
            );
        }
        $strZoom = HtmlUtils::getBalise(
            self::TAG_UL,
            $content,
            [
                self::ATTR_CLASS => 'zoomContentCol ui-menu ui-autocomplete list-group',
                self::ATTR_STYLE => 'width: 325px;',
            ]
        );
        ///////////////////////////////////////////////////////

        $arrReturned = ["content" => $strHeader.$strZoom];

        return '{"refresh": '.json_encode($arrReturned, JSON_THROW_ON_ERROR).'}';
    }
}
