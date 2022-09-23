<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsPlayerBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.28
 */
class CopsPlayerBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsPlayer = ($Obj==null ? new CopsPlayer() : $Obj);
    $this->CopsLangueServices = new CopsLangueServices();
  }

  /*
 * @since 1.22.04.28
 * @version 1.22.04.28
   */
  public function getCopsPlayerComps($isCreation=false)
  {
    return '';
  }

  public function getCopsPlayerCarac($isCreation=false)
  {
    $selectAttributes = array(
      'class'         => 'form-control ajaxAction',
      'data-trigger'  => 'change',
      'data-ajax'     => 'saveData,checkLangue',
    );

    $urlTemplate  = 'web/pages/public/fragments/public-fragments-section-caracteristiques-panel';
    $urlTemplate .= ($isCreation ? '-edit' : '').'.php';
    $attributes = array(
      // Carrure
      $this->CopsPlayer->getField('carac_carrure'),
      // Charme
      2,
      // Coordination
      2,
      // Education
      2,
      // Perception
      2,
      // Réflexes
      2,
      // Sang-froid
      2,
      // Points de vie max
      26,
      // Points d'adrénaline
      0,
      // Points d'ancienneté
      0,
      // Langue 2
      $this->CopsLangueServices->getSelectHtml(array_merge($selectAttributes, array('name'=>'carac-langue-02', 'id'=>'carac-langue-02',))),
      // Langue 3
      $this->CopsLangueServices->getSelectHtml(array_merge($selectAttributes, array('name'=>'carac-langue-03', 'id'=>'carac-langue-03', 'style'=>'display:none;'))),
      // Langue 4
      $this->CopsLangueServices->getSelectHtml(array_merge($selectAttributes, array('name'=>'carac-langue-04', 'id'=>'carac-langue-04', 'style'=>'display:none;'))),
      // Langue 5
      $this->CopsLangueServices->getSelectHtml(array_merge($selectAttributes, array('name'=>'carac-langue-05', 'id'=>'carac-langue-05', 'style'=>'display:none;'))),
      // Points de vie current
      26,
      // Points d'adrénaline current
      0,
      // Points d'ancienneté current
      0,
    );
    return $this->getRender($urlTemplate, $attributes);
  }

  public function getLibraryCard()
  {
    ////////////////////////////////////////////////////////////////////
    switch ($this->CopsPlayer->getField(self::FIELD_GRADE)) {
      case 'Capitaine' :
        $bordure = 'warning';
        $section = 'N/A';
      break;
      case 'Lieutenant' :
        $bordure = 'primary';
        $section = $this->CopsPlayer->getField(self::FIELD_SECTION);
      break;
      case 'Détective' :
        $bordure = 'light';
        $section = $this->CopsPlayer->getField(self::FIELD_SECTION);
      break;
      default :
        $bordure = '';
        $section = '';
      break;
    }
    ////////////////////////////////////////////////////////////////////
    if (in_array($section, array('N/A', 'A-Alpha', 'B-Epsilon'))) {
      $id = $this->CopsPlayer->getField(self::FIELD_ID);
      $mask = 'masks/mask-'.$id.($id=='51' ? '.png' : '.jpg');
    } else {
      $mask = 'masks/mask-000.jpg';
    }
    ////////////////////////////////////////////////////////////////////
    $matriculeId = substr($this->CopsPlayer->getField(self::FIELD_MATRICULE), 4);
    ////////////////////////////////////////////////////////////////////
    $surnom = $this->CopsPlayer->getField(self::FIELD_SURNOM);
    if ($surnom=='') {
      $surnom = '&nbsp;';
    }
    ////////////////////////////////////////////////////////////////////

    $urlTemplate  = 'web/pages/public/fragments/public-fragments-article-library-cops-extract.php';
    $attributes = array(
      // Couleur bordure    : warning / primary / ??
      $bordure,
      // Nom                : Skripnick Jason
      $this->CopsPlayer->getField(self::FIELD_NOM).' '.$this->CopsPlayer->getField(self::FIELD_PRENOM),
      // Masque ou Portrait : masks/mask-001.jpg
      $mask,
      // Surnom             : Capitaine
      $surnom,
      // Matricule          : 001
      $matriculeId,
      // Section            : N/A / A-Alpha
      $section,
    );
    return $this->getRender($urlTemplate, $attributes);
  }
}
