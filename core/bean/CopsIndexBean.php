<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsIndexBean
 * @author Hugues
 * @since 1.22.10.21
 * @version 1.22.10.21
 */
class CopsIndexBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = ($objStd==null ? new CopsIndex() : $objStd);
    }

    /**
     * @return string
     * @since 1.22.10.21
     * @version 1.22.10.21
     */
    public function getCopsIndexRow($blnShowColNature, $hasCopsEditor=false)
    {
		// Si $blnShowColNature, on affiche la colonne Nature.
		// On veut afficher le nom, la nature (opt) et la description.
		
		$arrColumns = array();
		// Checkbox ?
		
		// Le nom.
		$aAttributes = array(self::ATTR_CLASS=>'text-white');
		$label = $this->getBalise(self::TAG_SPAN, $this->obj->getField('nomIdx'));
		$lienEdition = $this->getBalise(self::TAG_A, $label, $aAttributes);
		$cell = $this->getBalise(self::TAG_TD, $lienEdition, array(self::ATTR_CLASS=>'mailbox-name'));
		$arrColumns[] = $cell;
		
		// La nature
		if ($blnShowColNature) {
			$label = $this->obj->getNature()->getField('nomIdxNature');
			$cell = $this->getBalise(self::TAG_TD, $label, array(self::ATTR_CLASS=>'mailbox-date'));
			$arrColumns[] = $cell;
		}
		
		// La description
		$label = $this->obj->getField('descriptionPJ');
		$cell = $this->getBalise(self::TAG_TD, $label, array(self::ATTR_CLASS=>'mailbox-name'));
		$arrColumns[] = $cell;
		
		// Lien d'édition
		if ($hasCopsEditor) {
			$label ='<button type="button" class="btn btn-default btn-sm" title="Modifier cette entrée"><a href="/admin?onglet=library&amp;subOnglet=index&amp;action=write&amp;id='.$this->obj->getField(self::FIELD_ID).'" class="text-white"><i class="fa-solid fa-square-pen"></i></a></button>';
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

}