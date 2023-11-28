<?php
namespace core\bean;

use core\services\CopsCalAddressServices;
use core\services\CopsCalPhoneServices;
use core\services\CopsCalGuyServices;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * CopsCalGuyBean
 * @author Hugues
 * @since v1.23.11.25
 * @version v1.23.12.02
 */
class CopsCalGuyBean extends CopsBean
{
    private $obj;
    private $objServices;
    private $nbFieldsPerRow;

    /**
     * @since v1.23.11.25
     */
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = $objStd;
        $this->initServices();
    }

    /**
     * @since v1.23.11.25
     */
    private function initServices()
    {
        $this->objServices = new CopsCalGuyServices();
    }

    //////////////////////////////////////////////////////
    // TABLE METHODS
    //////////////////////////////////////////////////////
    /**
     * @since v1.23.11.25
     */
    public function getTableHeader(array &$queryArg=[]): TableauTHeadHtmlBean
    {
        //////////////////////////////////////////////////////
        // Définition du Header du tableau
        $headerElement = [
            [self::FIELD_LABEL => self::LABEL_NOM, self::ATTR_CLASS => ' col-9'],
            [self::FIELD_LABEL => self::LABEL_BIRTHDAY, self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => self::LABEL_WEIGHT, self::ATTR_CLASS => self::CSS_COL_1],
            [self::FIELD_LABEL => self::LABEL_HEIGHT, self::ATTR_CLASS => self::CSS_COL_1],
        ];
        $this->nbFieldsPerRow = 4;

        return $this->getBuiltTableHeader($headerElement, $queryArg);
    }

    /**
     * @since v1.23.11.25
     */
    public function getTooMuchRows(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $strContent = self::LABEL_SEARCH_TOO_MUCH_DATA;
        $attributes = [
            self::CST_COLSPAN => $this->nbFieldsPerRow,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, self::CSS_TEXT_START, $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.11.25
     */
    public function getEmptyRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $strContent = self::LABEL_SEARCH_NO_DATA;
        $attributes = [
            self::CST_COLSPAN => $this->nbFieldsPerRow,
        ];
        $objRow->addCell(new TableauCellHtmlBean($strContent, self::TAG_TD, self::CSS_TEXT_START, $attributes));
        return $objRow;
    }

    /**
     * @since v1.23.11.25
     */
    public function getTableRow(bool $adminView=false): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        // Lien vers le détail
        if ($adminView) {
            $urlElements = [
                self::CST_ONGLET => self::ONGLET_RND_GUY,
                self::CST_SUBONGLET => self::CST_HOME,
                self::FIELD_ID => $this->obj->getField(self::FIELD_ID),
                self::CST_ACTION => self::CST_WRITE,
            ];
            $strLink = HtmlUtils::getLink(
                $this->obj->getFullName(),
                UrlUtils::getAdminUrl($urlElements),
            );

        } else {
            $urlElements = [
                self::WP_PAGE=>self::PAGE_ADMIN,
                self::CST_ONGLET => self::ONGLET_BDD,
                self::CST_SUBONGLET => self::CST_BDD_RESULT,
                self::FIELD_GENKEY => $this->obj->getField(self::FIELD_GENKEY),
            ];
            $strLink = HtmlUtils::getLink(
                $this->obj->getFullName(),
                UrlUtils::getPublicUrl($urlElements),
            );
        }
        $objRow->addCell(new TableauCellHtmlBean($strLink, self::TAG_TD, self::CSS_TEXT_START));
        // Date de naissance
        $value = $this->obj->getField(self::FIELD_BIRTHDAY);
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));
        // Date de naissance
        $value = $this->obj->getField(self::FIELD_KILOGRAMS).'kg';
        $objRow->addCell(new TableauCellHtmlBean($value, self::TAG_TD, self::CSS_TEXT_END));
        // Date de naissance
        $cms = $this->obj->getField(self::FIELD_CENTIMETERS);
        $taille = str_replace('.', 'm', str_pad($cms/100, 4, '0'));
        $objRow->addCell(new TableauCellHtmlBean($taille, self::TAG_TD, self::CSS_TEXT_END));

        return $objRow;
    }

    /**
     * @since v1.23.11.25
     */
    public function getTableFooter(array $attributes=[]): TableauTFootHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        $objRow->addStyle(self::CSS_LINE_HEIGHT_30PX);
        // Pas de filtre sur le nom
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre sur la date de naissance
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // Pas de filtre
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        // On ajoute le footer
        $objFooter = new TableauTFootHtmlBean();
        $objFooter->addRow($objRow);
        return $objFooter;
    }
    //////////////////////////////////////////////////////

    /**
     * @since v1.23.11.25
     */
    public function getNameSetFilter($selectedValue=''): string
    {
        $urlElements = [
            self::CST_ONGLET => self::ONGLET_BDD,
            self::CST_SUBONGLET => self::CST_BDD_RESULT,
        ];
        $field = self::FIELD_NAMESET;
        $attributes = [
            self::SQL_ORDER_BY => $field,
        ];
        $objs = $this->objServices->getCalGuys($attributes);
        $strLabel = ucfirst(self::FIELD_NAMESET);
        $filter = self::FIELD_NAMESET;

        return $this->getMutualFilter($urlElements, $selectedValue, $strLabel, $objs, $field, $filter);
    }

    /**
     * @since v1.23.11.25
     */
    public function getDetailInterface(bool $readonly=false): array
    {
        $spanCol3 = [self::ATTR_CLASS=>'input-group-text col-3'];
        $spanCol5 = [self::ATTR_CLASS=>'input-group-text col-5'];
        $inpAttributes = $readonly ? [self::CST_READONLY=>self::CST_READONLY] : [];

        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_CIVILITE, $spanCol3);
        $inpTitle = $span.$this->getSelectContent(self::FIELD_TITLE, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_FIRST_NAME, $spanCol3);
        $inpFirstName = $span.$this->getInputContent(self::FIELD_FIRSTNAME, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_NOM, $spanCol3);
        $inpLastName = $span.$this->getInputContent(self::FIELD_LASTNAME, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_BIRTHDAY, $spanCol5);
        $inpBirthday = $span.$this->getInputContent(self::FIELD_BIRTHDAY, $inpAttributes);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_WEIGHT, $spanCol3);
        $poids = $this->obj->getField(self::FIELD_KILOGRAMS);
        if ($poids!='') {
            $poids .= 'kg';
        }
        $inpKilograms = $span.$this->getInputContent(self::FIELD_KILOGRAMS, $inpAttributes, $poids);
        /////////////////////////////////////////
        $span = HtmlUtils::getBalise(self::TAG_SPAN, self::LABEL_HEIGHT, $spanCol3);
        $cms = $this->obj->getField(self::FIELD_CENTIMETERS);
        if ($cms!='') {
            $taille = str_replace('.', 'm', str_pad($cms/100, 4, '0'));
        } else {
            $taille = '';
        }
        $inpCentimeters = $span.$this->getInputContent(self::FIELD_CENTIMETERS, $inpAttributes, $taille);
        /////////////////////////////////////////

        return [
            $inpTitle,
            $inpFirstName,
            $inpLastName,
            $inpBirthday,
            $inpKilograms,
            $inpCentimeters,
        ];
    }

    /**
     * @since v1.23.11.25
     */
    public function getSelectContent($field, array $extraAttributes=[])
    {
        $selValue = $this->obj->getField($field);
        $strContentSel = HtmlUtils::getOption();
        $objs = $this->objServices->getDistinctCalGuyField($field);
        while (!empty($objs)) {
            $obj = array_shift($objs);
            $value = $obj->getField($field);
            $strContentSel .= HtmlUtils::getOption($value, $value, $value==$selValue);
        }
        $attributes = [
            self::ATTR_CLASS => self::CSS_CUSTOM_SELECT,
            self::ATTR_NAME  => $field,
            self::FIELD_ID   => $field,
        ];
        return HtmlUtils::getBalise(self::TAG_SELECT, $strContentSel, array_merge($attributes, $extraAttributes));
    }

    /**
     * @since v1.23.11.25
     */
    public function getInputContent(string $field, array $extraAttributes=[], $inpValue=''): string
    {
        if ($inpValue=='') {
            $inpValue = $this->obj->getField($field);
        }
        if ($inpValue==self::SQL_JOKER_SEARCH) {
            $inpValue = '';
        }
        $attributes = [
            self::ATTR_TYPE  => 'text',
            self::ATTR_CLASS => 'form-control col',
            self::FIELD_ID => $field,
            self::ATTR_NAME => $field,
            self::ATTR_VALUE => $inpValue,
        ];
        return HtmlUtils::getBalise(self::TAG_INPUT, '', array_merge($attributes, $extraAttributes));
    }

    /**
     * @since v1.23.12.02
     */
    public function getAddressBlock(bool $edition=false): string
    {
        $objs = $this->obj->getCalGuyAddresses();
        $str = '';
        // Si je ne suis pas en édition, je vais retourner une simple liste
        // Si je suis en édition, je dois retourner des champs de saisie pour les différents éléments
        // Et je dois envoyer aussi une ligne libre pour pouvoir ajouter une nouvelle saisie
        if ($edition) {
            $str .= '<div class="card card-outline card-info col-12 mt-2 p-0 mx-1">';
            $str .= '<div class="card-body row"><div class="col-12 input-group mb-3">';
        }
        $str .= '<ul>';
        if (empty($objs)) {
            $str .= '<li>Aucune adresse recensée.</li>';
        } else {
            foreach ($objs as $obj) {
                $str .= $obj->getBean()->getListAddress($edition);
            }
        }
        $str .= '</ul>';
        if ($edition) {
            $strContent = '';
            // Saisie du numéro de rue.
            $attributes = [
                self::ATTR_TYPE => 'text',
                self::ATTR_CLASS => 'form-control col-1',
                self::ATTR_VALUE => '',
                self::FIELD_ID => self::FIELD_NUMBER,
                self::ATTR_NAME => self::FIELD_NUMBER,
            ];
            $strContent .= HtmlUtils::getBalise(self::TAG_INPUT, '', $attributes);
            // Saisie de streetDirection
            $strContent .= $this->addDropdown(self::FIELD_ST_DIRECTION);
            // Saisie de streetName
            $strContent .= $this->addDropdown(self::FIELD_ST_NAME);
            // Saisie de streetSuffix
            $strContent .= $this->addDropdown(self::FIELD_ST_SUFFIX);
            // Saisie de streetSuffixDirection
            $strContent .= $this->addDropdown(self::FIELD_ST_SUF_DIRECTION);
            // Saisie de zipcode
            $strContent .= $this->addDropdown(self::FIELD_ZIPCODE);

            $strContent .= $this->addCancelSubmitButtons('cleanGuyAddress', 'insertGuyAddress,cleanGuyAddress');

            $str .= '<div class="input-group input-group-sm">'.$strContent.'</div>';
            $str .= '</div></div></div>';
        }
        return $str;
    }


    /**
     * @since v1.23.12.02
     */
    public function getPhoneBlock(bool $edition=false): string
    {
        $objs = $this->obj->getCalGuyPhones();
        $str = '';
        // Si je ne suis pas en édition, je vais retourner une simple liste
        // Si je suis en édition, je dois retourner des champs de saisie pour les différents éléments
        // Et je dois envoyer aussi une ligne libre pour pouvoir ajouter une nouvelle saisie
        if ($edition) {
            $str .= '<div class="card card-outline card-info col-12 mt-2 p-0 mx-1">';
            $str .= '<div class="card-body row"><div class="col-12 input-group mb-3">';
        }
        $str .= '<ul>';
        if (empty($objs)) {
            $str .= '<li>Aucun téléphone recensé.</li>';
        } else {
            foreach ($objs as $obj) {
                $str .= $obj->getBean()->getListPhone($edition);
            }
        }
        $str .= '</ul>';
        if ($edition) {
            $strContent = '';

            // Saisie de la ville
            $strContent .= $this->addDropdown(self::FIELD_CITY_NAME);

            // On ajoute un petit vide
            $strContent .= HtmlUtils::getLink(self::CST_NBSP, '#', 'btn btn-outline btn-sm');

            // Saisie du premier élément
            $strContent .= $this->addDropdown(self::CST_PN_FIRST);
        
            $strContent .= HtmlUtils::getLink('-', '#', 'btn btn-outline btn-sm');

            // Saisie du deuxième élément
            $strContent .= $this->addDropdown(self::CST_PN_SECOND);
        
            $strContent .= HtmlUtils::getLink('-', '#', 'btn btn-outline btn-sm');

            // Saisie du libre. S'il n'est pas saisi, il sera généré aléatoirement.
            $attributes = [
                self::ATTR_TYPE => 'text',
                self::ATTR_CLASS => 'form-control col-1',
                self::ATTR_VALUE => '',
                self::FIELD_ID => self::CST_PN_THIRD,
                self::ATTR_NAME => self::CST_PN_THIRD,
            ];
            $strContent .= HtmlUtils::getBalise(self::TAG_INPUT, '', $attributes);

            $strContent .= $this->addCancelSubmitButtons('cleanGuyPhone', 'insertGuyPhone,cleanGuyPhone');

            $str .= '<div class="input-group input-group-sm">'.$strContent.'</div>';
            $str .= '</div></div></div>';
        }
        return $str;
    }

    /**
     * @since v1.23.12.02
     */
    public function addDropdown(string $field): string
    {
        $strContent = '';
        $blnPhone = in_array($field, [self::FIELD_CITY_NAME, self::CST_PN_FIRST, self::CST_PN_SECOND]);

        /////////////////////////////////////////////
        // Création de l'input
        $attributes = [
            self::ATTR_TYPE => 'text',
            self::ATTR_CLASS => 'form-control col-1 '.self::AJAX_ACTION,
            self::FIELD_ID => $field,
            self::ATTR_NAME => $field,
            self::ATTR_VALUE => '',
        ];
        // Certains sont readonly
        if (in_array($field, [self::FIELD_ST_DIRECTION, self::FIELD_ST_SUFFIX, self::FIELD_ST_SUF_DIRECTION])) {
            $attributes[self::CST_READONLY] = self::CST_READONLY;
        }
        // Certains peuvent filter le dropdown
        if (in_array($field, [self::FIELD_ST_NAME, self::FIELD_CITY_NAME, self::CST_PN_FIRST, self::CST_PN_SECOND])) {
            $attributes[self::ATTR_CLASS] .= ' '.self::AJAX_ACTION;
            $attributes[self::ATTR_DATA] = [
                self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_KEYUP,
                self::ATTR_DATA_AJAX    => 'filter',
                self::ATTR_DATA_TARGET  => '#dropDown'.$field
            ];
        }
        // On créé la balise
        $strContent .= HtmlUtils::getBalise(self::TAG_INPUT, '', $attributes);
        /////////////////////////////////////////////

        /////////////////////////////////////////////
        // Création du bouton de dropdown
        $attributes = [
            self::ATTR_CLASS => 'btn btn-outline-secondary dropdown-toggle',
            self::ATTR_DATA_BS_TOGGLE => 'dropdown',
            'aria-expanded'  => 'false',
        ];
        $strContent .= HtmlUtils::getButton('', $attributes);
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Création du dropdown à proprement parlé
        $strUlContent = '';

        $objServices = $blnPhone ? new CopsCalPhoneServices() : new CopsCalAddressServices();
        if ($field==self::CST_PN_FIRST) {
            $objs = $objServices->getDistinctFirstTrigramme();
        } elseif ($field==self::CST_PN_SECOND) {
            $objs = $objServices->getDistinctSecondTrigramme();
        } else {
            $objs = $objServices->getDistinctFieldValues($field);
        }

        $linkAttributes = [
            self::ATTR_DATA => [
                self::ATTR_DATA_TARGET  => '#'.$field,
                self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CLICK,
                self::ATTR_DATA_AJAX    => $blnPhone ? 'phoneDropdown' : 'addressDropdown'
            ]
        ];

        foreach ($objs as $obj) {
            if ($field==self::CST_PN_FIRST) {
                $value = $obj->getField(self::FIELD_PHONE_ID);
                $value = substr($value, 0, 3);
            } elseif ($field==self::CST_PN_SECOND) {
                $value = $obj->getField(self::FIELD_PHONE_ID);
                $value = mb_substr($value, 4, 3);
            } else {
                $value = $obj->getField($field);
            }
            $href = '#';
            $strLink = HtmlUtils::getLink($value, $href, 'dropdown-item ajaxAction', $linkAttributes);
            $liAttributes = ['data-value' => $value];
            $strUlContent .= HtmlUtils::getBalise(self::TAG_LI, $strLink, $liAttributes);
        }
        $ulAttributes = [
            self::FIELD_ID => 'dropDown'.$field,
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'max-height: 200px; overflow: hidden auto;',
        ];
        /////////////////////////////////////////////

        /////////////////////////////////////////////
        // Retour de l'ensemble
        return $strContent.HtmlUtils::getBalise(self::TAG_UL, $strUlContent, $ulAttributes);
    }

    /**
     * @since v1.23.12.02
     */
    public function addCancelSubmitButton(string $actionCancel, string $actionSubmit): string
    {
            // On ajoute un petit vide
            $strContent = HtmlUtils::getLink(self::CST_NBSP, '#', 'btn btn-outline btn-sm');

            // Ajout Bouton Annuler
            // On vide les champs de chaque zone de saisie et on rafraichit la liste des dropdown
            $strIcon = HtmlUtils::getIcon(self::I_REFRESH);
            $aAttributes = [
                self::ATTR_DATA => [
                    self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CLICK,
                    self::ATTR_DATA_AJAX    => $actionCancel,
                ]
            ];
            $strContent .= HtmlUtils::getLink($strIcon, '#', 'btn btn-outline btn-sm ajaxAction', $aAttributes);
            
            // Ajout Bouton Envoyer
            // On envoye les données pour enregistrement (si valides) et on recharge la page.
            $strIcon = HtmlUtils::getIcon(self::I_PAPER_PLANE);
            $aAttributes = [
                self::ATTR_DATA => [
                    self::ATTR_DATA_TRIGGER => self::AJAX_ACTION_CLICK,
                    self::ATTR_DATA_AJAX    => $actionSubmit,
                ]
            ];
            return $strContent.HtmlUtils::getLink($strIcon, '#', 'btn btn-primary btn-sm ajaxAction', $aAttributes);
    }
}
