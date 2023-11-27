<?php
namespace core\bean;

use core\domain\CopsIndexClass;
use core\services\CopsIndexServices;
use core\utils\HtmlUtils;

/**
 * CopsIndexReferenceBean
 * @author Hugues
 * @since 1.23.02.18
 * @version v1.23.12.02
 */
class CopsIndexReferenceBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj = ($objStd==null ? new CopsIndexClass() : $objStd);
        
        // On initialise l'éventuelle pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // On initialise l'éventuelle catégorie
        $this->catSlug = $this->initVar(self::CST_CAT_SLUG, '');

        $this->objCopsIndexServices = new CopsIndexServices();
    }

    /**
     * @since 1.22.10.21
     * @version v1.23.12.02
     */
    public function getCopsIndexReferenceRow(string $url, bool $blnShowColNature, bool $hasCopsEditor=false): string
    {
        // Si $blnShowColNature, on affiche la colonne Nature.
        // On veut afficher le nom, la nature (opt) et la description.
        $arrColumns = [];
        // Checkbox ?
        
        // Le nom.
        if ($hasCopsEditor) {
            $url .= '&amp;'.self::CST_ACTION.'='.self::CST_ENQUETE_WRITE;
            $url .= '&amp;id='.$this->obj->getField(self::FIELD_ID_IDX_REF);
        } else {
            $url = '#';
        }
        $spanLabel = $this->getBalise('strong', $this->obj->getField(self::FIELD_NOM_IDX));
        if (!empty($this->obj->getField(self::FIELD_PRENOM_IDX))) {
            $spanLabel .= ' '.$this->obj->getField(self::FIELD_PRENOM_IDX);
        }
        $label = $this->getBalise(self::TAG_SPAN, $spanLabel);
        $lienEdition = HtmlUtils::getLink($label, $url, self::CST_TEXT_WHITE);
        $cell = $this->getBalise(self::TAG_TD, $lienEdition, [self::ATTR_CLASS=>'mailbox-name']);
        $arrColumns[] = $cell;
        
        // La nature
        if ($blnShowColNature) {
            $label = $this->obj->getNature()->getField(self::FIELD_NOM_IDX_NATURE);
            $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-date']);
            $arrColumns[] = $cell;
        }
        
        // La description
        $label = $this->obj->getField(self::FIELD_DESCRIPTION_PJ);
        $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-name']);
        $arrColumns[] = $cell;

        // La description MJ
        if ($hasCopsEditor) {
            $label = $this->obj->getField(self::FIELD_DESCRIPTION_MJ);
            $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-name']);
            $arrColumns[] = $cell;
        }

        // La référence
        $label = $this->getReferences();
        $cell = $this->getBalise(self::TAG_TD, $label, [self::ATTR_CLASS=>'mailbox-name']);
        $arrColumns[] = $cell;
        
        // Lien d'édition
        if ($hasCopsEditor) {
            $aContent = HtmlUtils::getIcon(self::I_SQUARE_PEN);
            $buttonContent = HtmlUtils::getLink($aContent, $url, self::CST_TEXT_WHITE);
            $buttonAttributes = [
                self::ATTR_TITLE => self::LABEL_EDIT_ENTRY
            ];
            $label = HtmlUtils::getButton($buttonContent, $buttonAttributes);
            $cell = $this->getBalise(self::TAG_TD, $label);
            $arrColumns[] = $cell;
        }

        // Construction de la ligne
        $rowContent = '';
        while (!empty($arrColumns)) {
            $td = array_shift($arrColumns);
            $rowContent .= $td;
        }
        return $this->getBalise(self::TAG_TR, $rowContent);
    }

    public function getReferences()
    {
        // \p{Lu} correspond à A-Z
        $strPattern = '/([\p{Lu}]*)([0-9\.\/]*)/';
        $label = $this->obj->getField(self::FIELD_REFERENCE);
        if (empty($label)) {
            // Soit, le champ est vide parce qu'il n'y aucune référence.
            // Soit il faut récupérer les informations dans cops_index.
            $attributes = [self::FIELD_REF_IDX_ID=>$this->obj->getField(self::FIELD_ID_IDX_REF)];
            $objsCopsIndex = $this->objCopsIndexServices->getIndexes($attributes);
            $arrLabels = [];
            while (!empty($objsCopsIndex)) {
                $objCopsIndex = array_shift($objsCopsIndex);
                $arrLabels[] = $objCopsIndex->getTomeAndPage();
            }
            if (!empty($arrLabels)) {
                $label = implode(', ', $arrLabels);
            }
        } else {
            // Si $label n'est pas vide, on doit transférer les informations vers
            // cops_index puis éditer la donnée en base pour vider le champ reference
            // TODO : A terme, il faudra supprimer ce mécanisme car toutes les données auront été transférées.
            $arrReferences = explode(', ', (string) $label);

            $label = '';
            $blnUpdate = false;
            foreach ($arrReferences as $strRef) {
                if (preg_match($strPattern, $strRef, $matches)) {
                    $label .= $strRef.'*'.$matches[1].'/'.$matches[2].'<br>';
                    $objCopsTome = $this->objCopsIndexServices->getIndexTomeByAbr($matches[1]);
                    $attributes = [
                        self::FIELD_REF_IDX_ID => $this->obj->getField(self::FIELD_ID_IDX_REF),
                        self::FIELD_TOME_IDX_ID => $objCopsTome->getField(self::FIELD_ID_IDX_TOME),
                        self::FIELD_PAGE => $matches[2]
                    ];
                    $objCopsIndex = new CopsIndexClass($attributes);
                    $this->objCopsIndexServices->insertIndex($objCopsIndex);
                    $blnUpdate = true;
                } else {
                    $label .= $strRef.'<br>';
                }
            }
            if ($blnUpdate) {
                $this->obj->setField('reference', '');
                $this->objCopsIndexServices->updateIndexReference($this->obj);
            }
        }
        return $label;
    }

}
