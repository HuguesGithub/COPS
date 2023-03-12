<?php
namespace core\bean;

use core\domain\CopsIndexClass;
use core\services\CopsIndexServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsIndexReferenceBean
 * @author Hugues
 * @since 1.23.02.18
 * @version 1.23.02.18
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
     * @return string
     * @since 1.22.10.21
     * @version 1.22.10.22
     */
    public function getCopsIndexReferenceRow($url, $blnShowColNature, $hasCopsEditor=false)
    {
        // Si $blnShowColNature, on affiche la colonne Nature.
        // On veut afficher le nom, la nature (opt) et la description.
        $url .= '&amp;'.self::CST_ACTION.'='.self::CST_ENQUETE_WRITE;
        $url .= '&amp;id='.$this->obj->getField(self::FIELD_ID_IDX_REF);
        
        $arrColumns = array();
        // Checkbox ?
        
        // Le nom.
        $aAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        if ($hasCopsEditor) {
            $aAttributes[self::ATTR_HREF] = $url;
        }
        $spanLabel = $this->getBalise('strong', $this->obj->getField(self::FIELD_NOM_IDX));
        if (!empty($this->obj->getField(self::FIELD_PRENOM_IDX))) {
            $spanLabel .= ' '.$this->obj->getField(self::FIELD_PRENOM_IDX);
        }
        $label = $this->getBalise(self::TAG_SPAN, $spanLabel);
        $lienEdition = $this->getBalise(self::TAG_A, $label, $aAttributes);
        $cell = $this->getBalise(self::TAG_TD, $lienEdition, array(self::ATTR_CLASS=>'mailbox-name'));
        $arrColumns[] = $cell;
        
        // La nature
        if ($blnShowColNature) {
            $label = $this->obj->getNature()->getField(self::FIELD_NOM_IDX_NATURE);
            $cell = $this->getBalise(self::TAG_TD, $label, array(self::ATTR_CLASS=>'mailbox-date'));
            $arrColumns[] = $cell;
        }
        
        // La description
        $label = $this->obj->getField(self::FIELD_DESCRIPTION_PJ);
        $cell = $this->getBalise(self::TAG_TD, $label, array(self::ATTR_CLASS=>'mailbox-name'));
        $arrColumns[] = $cell;
        // TODO : Ca serait bien de récupérer la description MJ...

        // La référence
        $label = $this->getReferences();
        $cell = $this->getBalise(self::TAG_TD, $label, array(self::ATTR_CLASS=>'mailbox-name'));
        $arrColumns[] = $cell;
        
        // Lien d'édition
        if ($hasCopsEditor) {
            $aContent = $this->getIcon('square-pen');
            $aAttributes = array(
                self::ATTR_HREF => $url,
                self::ATTR_CLASS => self::CST_TEXT_WHITE,
            );
            $buttonContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
            $buttonAttributes = array(
                self::ATTR_TYPE => self::TAG_BUTTON,
                self::ATTR_CLASS => 'btn btn-default btn-sm',
                self::ATTR_TITLE => self::LABEL_EDIT_ENTRY,
            );
            $label = $this->getBalise(self::TAG_BUTTON, $buttonContent, $buttonAttributes);
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
        $strPattern = '/([A-Z]*)([0-9\.\/]*)/';
        $label = $this->obj->getField('reference');
        if (empty($label)) {
            // Soit, le champ est vide parce qu'il n'y aucune référence.
            // Soit il faut récupérer les informations dans cops_index.
            $attributes = array(self::FIELD_REF_IDX_ID=>$this->obj->getField(self::FIELD_ID_IDX_REF));
            $objsCopsIndex = $this->objCopsIndexServices->getIndexes($attributes);
            $arrLabels = array();
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
            $arrReferences = explode(', ', $label);

            $label = '';
            $blnUpdate = false;
            foreach ($arrReferences as $strRef) {
                if (preg_match($strPattern, $strRef, $matches)) {
                    $label .= $strRef.'*'.$matches[1].'/'.$matches[2].'<br>';
                    $objCopsTome = $this->objCopsIndexServices->getIndexTomeByAbr($matches[1]);
                    $attributes = array(
                        self::FIELD_REF_IDX_ID => $this->obj->getField(self::FIELD_ID_IDX_REF),
                        self::FIELD_TOME_IDX_ID => $objCopsTome->getField(self::FIELD_ID_IDX_TOME),
                        self::FIELD_PAGE => $matches[2],
                    );
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
