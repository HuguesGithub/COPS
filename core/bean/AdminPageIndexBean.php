<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminPageIndexBean
 * @author Hugues
 * @version 1.22.09.06
 * @since 1.22.09.06
 */
class AdminPageIndexBean extends AdminPageBean
{
  protected $urlTemplateAdminPageIndex       = 'web/pages/admin/page-admin-index.php';

  /**
   * Class Constructor
   */
  public function __construct($urlParams=null)
  {
    parent::__construct();
  }

  /**
   * @param array $urlParams
   * @return string
   * @version 1.22.09.06
   * @since 1.22.09.06
   */
  public static function getStaticContentPage($urlParams)
  {
    ///////////////////////////////////////////:
    // Initialisation des valeurs par défaut
    $Bean = new AdminPageIndexBean($urlParams);
    return $Bean->getContentPage();
  }
  /**
   * @param array $urlParams
   * @return string
   * @version 1.22.09.06
   * @since 1.22.09.06
   */
  public function getContentPage()
  {
    if (isset($_POST) && !empty($_POST)) {
      $this->controlerEtEnregistrerIndex();
    }

    // On va afficher la dernière donnée enregistrée
    // Et on veut permettre d'aller chercher la suivante pour mettre à jour les données correspondantes.
    $attributes = array(
      //  - 1
      $this->getSelectNature(),
      //  - 2
      $this->getLisReference(),
      // - 3
      $this->getListeIndex(),
      // - 4
      $this->getLisNatureCheckboxes(),
      // - 5
      $this->getLisReferenceCheckboxes(),
    );
    return $this->getRender($this->urlTemplateAdminPageIndex, $attributes);
  }

  public function getLisReference()
  {
    $str_requete = "SELECT nomIdxReference, abrIdxReference FROM ";
    $str_requete .= "wp_7_cops_index_reference ORDER BY idIdxReference ASC;";
    $rows = MySQL::wpdbSelect($str_requete);

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
    $rows = MySQL::wpdbSelect($str_requete);

    $str_select  = '<select id="natureId" name="natureId" class="form-select">';
    $str_select .= '<option value="-">Choisir une Nature</option>';
    foreach ($rows as $row) {
      $str_select .= '<option value="'.$row->idIdxNature.'">'.$row->nomIdxNature.'</option>';
    }
    $str_select .= '</select>';
    return $str_select;
  }

  public function controlerEtEnregistrerIndex()
  {
    $CopsIndex = new CopsIndex();
    $CopsIndex->setField('nomIdx', $_POST['nomIdx']);
    $CopsIndex->setField('natureId', $_POST['natureId']);
    $CopsIndex->setField('reference', $_POST['reference']);
    $CopsIndex->setField('descriptionMJ', $_POST['descriptionMJ']);
    $CopsIndex->setField('descriptionPJ', $_POST['descriptionPJ']);

    $CopsIndex->insertCopsIndex();
  }

  public function getListeIndex()
  {
    $requete  = "SELECT nomIdx, nomIdxNature, reference, descriptionMJ, descriptionPJ FROM wp_7_cops_index ";
    $requete .= "INNER JOIN wp_7_cops_index_nature ON natureId=IdIdxNature ORDER BY nomIdx ASC;";
    $rows = MySQL::wpdbSelect($requete);

    $str_content = '';
    foreach ($rows as $row) {
      $str_content .= '<tr>';
      $str_content .= '<td>'.$row->nomIdx.'</td>';
      $str_content .= '<td>'.$row->nomIdxNature.'</td>';
      $str_content .= '<td>'.$row->reference.'</td>';
      $str_content .= '<td>MJ : '.$row->descriptionMJ.'<hr>PJ : '.$row->descriptionPJ.'</td>';
      $str_content .= '</tr>';
    }
    return $str_content;

  }

  public function getLisReferenceCheckboxes()
  {
    $str_requete  = "SELECT nomIdxReference, abrIdxReference ";
    $str_requete .= "FROM wp_7_cops_index_reference ORDER BY idIdxReference ASC;";
    $rows = MySQL::wpdbSelect($str_requete);

    $str_lis  = '';
    foreach ($rows as $row) {
      $str_lis .= '<li><a class="dropdown-item" href="#" data-abr="'.$row->abrIdxReference.'">';
      $str_lis .= '<label class="checkbox" title=""><input type="checkbox"> '.$row->nomIdxReference.'</label></a></li>';
    }
    return $str_lis;
  }

  public function getLisNatureCheckboxes()
  {
    $str_requete = "SELECT idIdxNature, nomIdxNature FROM wp_7_cops_index_nature ORDER BY nomIdxNature ASC;";
    $rows = MySQL::wpdbSelect($str_requete);

    $str_lis  = '';
    foreach ($rows as $row) {
      $str_lis .= '<li><a class="dropdown-item" href="#" data-abr="'.$row->idIdxNature.'">';
      $str_lis .= '<label class="checkbox" title=""><input type="checkbox"> '.$row->nomIdxNature.'</label></a></li>';
    }
    return $str_lis;
  }

}

