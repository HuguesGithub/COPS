<?php
namespace core\bean;

use core\domain\CopsIndexReferenceClass;
use core\domain\MySQLClass;
use core\utils\SessionUtils;

/**
 * AdminPageIndexBean
 * @author Hugues
 * @since v1.23.04.20
 * @version v1.23.06.25
 */
class AdminPageIndexBean extends AdminPageBean
{
    /**
     * @since 1.23.04.20
     */
    public static function getStaticContentPage(): string
    {
        ///////////////////////////////////////////:
        // Initialisation des valeurs par défaut
        $objBean = new AdminPageIndexBean();
        return $objBean->getContentPage();
    }

    /**
     * @since 1.23.04.20
     */
    public function getContentPage(): string
    {
        $this->controlerEtEnregistrerIndex();

        // On va afficher la dernière donnée enregistrée
        // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
        $attributes = [
            //  - 1
            '',//$this->getSelectNature(),
            //  - 2
            '',//$this->getLisReference(),
            // - 3
            '',//$this->getListeIndex(),
            // - 4
            '',//$this->getLisNatureCheckboxes(),
            // - 5
            '',//$this->getLisReferenceCheckboxes(),
        ];
        return $this->getRender(self::WEB_PA_INDEX, $attributes);
    }

    /**
     * @since v1.23.04.20
     * @version v1.23.06.25
     */
    public function controlerEtEnregistrerIndex(): void
    {
        // TODO : Envisager une édition si concerné.
        $objCopsIndexReference = new CopsIndexReferenceClass();
        $expectedFields = [
            self::FIELD_NOM_IDX,
            self::FIELD_NATURE_IDX_ID,
            self::FIELD_DESCRIPTION_MJ,
            self::FIELD_DESCRIPTION_PJ,
        ];
        while (!empty($expectedFields)) {
            $field = array_shift($expectedFields);
            $value = SessionUtils::fromPost($field);

            $objCopsIndexReference->setField($field, $value);
        }

        if ($objCopsIndexReference->isFieldsValid()) {
            $objCopsIndexReference->insertCopsIndexReference();
        }
    }
  


  public function getLisReference()
  {
    $str_requete = "SELECT nomIdxReference, abrIdxReference FROM ";
    $str_requete .= "wp_7_cops_index_reference ORDER BY idIdxReference ASC;";
    $rows = MySQLClass::wpdbSelect($str_requete);

    $str_lis  = '';
    foreach ($rows as $row) {
      $str_lis .= '<li><a class="dropdown-item" href="#" data-abr="'.$row->abrIdxReference.'">';
      $str_lis .= $row->nomIdxReference.'</a></li>';
    }
    return $str_lis;
  }

  public function getSelectNature()
  {
    $str_requete = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature ORDER BY nomIdxNature ASC;";
    $rows = MySQLClass::wpdbSelect($str_requete);

    $str_select  = '<select id="natureId" name="natureId" class="form-select">';
    $str_select .= '<option value="-">Choisir une Nature</option>';
    foreach ($rows as $row) {
      $str_select .= '<option value="'.$row->idIdxNature.'">'.$row->nomIdxNature.'</option>';
    }
    $str_select .= '</select>';
    return $str_select;
  }


  public function getListeIndex()
  {
    $requete  = "SELECT nomIdx, nomIdxNature, reference, descriptionMJ, descriptionPJ FROM wp_7_cops_index ";
    $requete .= "INNER JOIN wp_7_cops_index_nature ON natureId=IdIdxNature ORDER BY nomIdx ASC;";
    $rows = MySQLClass::wpdbSelect($requete);

    $str_content = '';
    foreach ($rows as $row) {
      $str_content .= '<tr>';
      $str_content .= $this->getBalise(self::TAG_TD, $row->nomIdx);
      $str_content .= $this->getBalise(self::TAG_TD, $row->nomIdxNature);
      $str_content .= $this->getBalise(self::TAG_TD, $row->reference);
      $str_content .= $this->getBalise(self::TAG_TD, 'MJ : '.$row->descriptionMJ.'<hr>PJ : '.$row->descriptionPJ);
      $str_content .= '</tr>';
    }
    return $str_content;

  }

    public function getLi($abrIdxReference, $nomIdxReference)
    {
        $str  = '<li><a class="dropdown-item" href="#" data-abr="'.$abrIdxReference.'">';
        $str .= '<label class="checkbox" title=""><input type="checkbox"> ';
        $str .= $nomIdxReference.'</label></a></li>';
        return $str;
    }
    public function getLisReferenceCheckboxes()
    {
        $strRequete  = "SELECT nomIdxReference, abrIdxReference ";
        $strRequete .= "FROM wp_7_cops_index_reference ORDER BY idIdxReference ASC;";
        $rows = MySQLClass::wpdbSelect($strRequete);

        $strLis  = '';
        foreach ($rows as $row) {
            $strLis .= $this->getLi($row->abrIdxReference, $row->nomIdxReference);
        }
        return $strLis;
    }

  public function getLisNatureCheckboxes()
  {
    $strRequete = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature ORDER BY nomIdxNature ASC;";
    $rows = MySQLClass::wpdbSelect($strRequete);

    $strLis  = '';
    foreach ($rows as $row) {
        $strLis .= $this->getLi($row->idIdxNature, $row->nomIdxNature);
    }
    return $strLis;
  }

}

