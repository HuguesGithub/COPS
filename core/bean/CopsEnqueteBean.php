<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEnqueteBean
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.10.06
 */
class CopsEnqueteBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = ($objStd==null ? new CopsEnquete() : $objStd);
        $this->urlOnglet   .= self::ONGLET_ENQUETE;
        $this->urlSubOnglet = $this->urlOnglet . '&amp;' . self::CST_SUBONGLET . '=';
        $this->strNoRapportDisponible = 'Aucune rapport disponible';
    }

    /**
     * @return string
     * @since 1.22.09.20
     * @version 1.22.10.06
     */
    public function getCopsEnqueteRow()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-enquete-row.php';
        $id          = $this->obj->getField(self::FIELD_ID);
        $intSince    = $this->obj->getField(self::FIELD_DSTART);
        $intLast     = $this->obj->getField(self::FIELD_DLAST);

        switch ($this->obj->getField(self::FIELD_STATUT_ENQUETE)) {
            case self::CST_ENQUETE_CLOSED :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_READ;
                $strActionsPossibles = '';
                break;
            case self::CST_ENQUETE_COLDED :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_READ;
                $strActionsPossibles  = $this->buildActionLink(
                    self::CST_FILE_OPENED, self::CST_ENQUETE_OPENED, self::I_FILE_OPENED, "Réouvrir l'enquête"
                );
                break;
            case self::CST_ENQUETE_OPENED :
            default :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_WRITE;
                $label = "Transférer au District Attorney";
                $strActionsPossibles  = $this->buildActionLink(
                    self::CST_FILE_CLOSED, self::CST_ENQUETE_CLOSED, self::I_FILE_CLOSED, $label
                );
                $strActionsPossibles .= '&nbsp;'.$this->buildActionLink(
                    self::CST_FILE_COLDED, self::CST_ENQUETE_COLDED, self::I_FILE_COLDED, "Classer l'enquête"
                );
                break;
        }

        $attributes = array(
            // Id
            $id,
            // Url de vision / édition, selon le statut.
            $urlViewEdit.'&amp;id='.$id,
            // Nom de l'enquête
            $this->obj->getField(self::FIELD_NOM_ENQUETE),
            // Date création
            $this->displayNiceDateSince($intSince),
            // Date dernière modification
            $this->displayNiceDateSince($intLast),
            // Actions possibles
            $strActionsPossibles,
        );

        return $this->getRender($urlTemplate, $attributes);
    }

    private function buildActionLink($subOnglet, $action, $icon, $title)
    {
        $id       = $this->obj->getField(self::FIELD_ID);
        $url      = $this->urlSubOnglet . $subOnglet;
        $url     .= '&amp;action='.$action.'&amp;id='.$id;
        $aContent = $this->getIcon($icon);
        return '<a href="'.$url.'" class="text-white" title="'.$title.'">'.$aContent.'</a>';
    }

    /**
     * @param integer $intDate
     * @return string
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function displayNiceDateSince($intDate)
    {
       $tsNow = UtilitiesBean::getCopsDate('tsnow');
       $tsDiff = $tsNow-$intDate;
       $strReturned = "Il y a ";
       if ($tsDiff<60) {
           $strReturned = "À l'instant";
       } elseif ($tsDiff<60*60) {
           $strReturned .= round($tsDiff/60)."min";
       } elseif ($tsDiff<60*60*24) {
           $strReturned .= round($tsDiff/(60*60))."hrs";
       } else {
           $strReturned .= round($tsDiff/(60*60*24))."j";
       }
       return $strReturned;
    }

    /**
     * @return string
     * @since 1.22.09.20
     * @version 1.22.10.04
     */
    public function getWriteEnqueteBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquete-write.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        // Gestion d'édition (création ou modification) d'un dossier d'enquête
        // On récupère l'objet CopsEnquete en fonction de l'id.
        // Attention, si CopsEnquete n'est pas ouvert, on doit rediriger vers une simple vision.

    $strSelectDistrictAttorneys = '';

    $strSQL  = "SELECT cbp.id AS cbpId, nomIdx ";
    $strSQL .= "FROM wp_7_cops_bdd_procureur AS cbp ";
    $strSQL .= "INNER JOIN wp_7_cops_index AS ci ON cbp.idxId=ci.id ";
    $strSQL .= "WHERE dateDebut<='".self::getCopsDate('Y-m-d')."' ";
    $strSQL .= "AND (dateFin>='".self::getCopsDate('Y-m-d')."' OR dateFin IS NULL) ";
    $strSQL .= "ORDER BY nomIdx ASC;";
    $rows = MySQL::wpdbSelect($strSQL);
    $sel = $this->obj->getField(self::FIELD_IDX_DISTRICT_ATT);
    while (!empty($rows)) {
      $row = array_shift($rows);
      $args = array(self::ATTR_VALUE=>$row->cbpId);
      if ($sel==$row->cbpId) {
          $args[self::CST_SELECTED] = self::CST_SELECTED;
      }
      $strSelectDistrictAttorneys .= $this->getBalise(self::TAG_OPTION, $row->nomIdx, $args);
    }

    $strRapportSID = $this->strNoRapportDisponible;
        // Récupération et construction des rapports d'autopsie.
        $objsAutopsie = $this->getCopsAutopsies();
        if (empty($objsAutopsie)) {
            $strRapportAutopsie = $this->strNoRapportDisponible;
        } else {
            $strRapportAutopsie = '';
            while (!empty($objsAutopsie)) {
                $objCopsAutopsie = array_shift($objsAutopsie);
                $strRapportAutopsie .= $objCopsAutopsie->getBean()->getCopsAutopsieLi();
            }
        }

        $attributes = array(
            // Id de l'enquête, s'il existe
            $this->obj->getField(self::FIELD_ID),
            // Url pour Annuler
            $this->urlOnglet,
            // Nom de l'enquête
            $this->obj->getField(self::FIELD_NOM_ENQUETE),
            // Select pour premier enquêteur
            '',
            // Select pour DA
            $strSelectDistrictAttorneys,
            // Résumé des faits
            $this->obj->getField(self::FIELD_RESUME_FAITS),
            // Scène de crime
            $this->obj->getField(self::FIELD_DESC_SCENE_CRIME),
            // Rapports FCID
            $strRapportSID,
            // Autopsies
            $strRapportAutopsie,
            // Pistes & Démarches
            $this->obj->getField(self::FIELD_PISTES_DEMARCHES),
            // Notes diverses
            $this->obj->getField(self::FIELD_NOTES_DIVERSES),
            // Enquêtes Personnalités
            '',
            // Témoins / Suspects
            '',
            // Chronologie
            '',

            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.06
     * @version 1.22.10.06
     */
    public function getReadEnqueteBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquete-read.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        $strRapportSID = $this->strNoRapportDisponible;
        // Récupération et construction des rapports d'autopsie.
        $objsAutopsie = $this->getCopsAutopsies();
        if (empty($objsAutopsie)) {
            $strRapportAutopsie = $this->strNoRapportDisponible;
        } else {
            $strRapportAutopsie = '';
            while (!empty($objsAutopsie)) {
                $objCopsAutopsie = array_shift($objsAutopsie);
                $strRapportAutopsie .= $objCopsAutopsie->getBean()->getCopsAutopsieLi();
            }
        }

        $strSQL  = "SELECT cbp.id AS cbpId, nomIdx ";
        $strSQL .= "FROM wp_7_cops_bdd_procureur AS cbp ";
        $strSQL .= "INNER JOIN wp_7_cops_index AS ci ON cbp.idxId=ci.id ";
        $strSQL .= "WHERE cbp.id = ".$this->obj->getField(self::FIELD_IDX_DISTRICT_ATT).";";
        $rows = MySQL::wpdbSelect($strSQL);
        if (!empty($rows)) {
          $row = array_shift($rows);
          $strSelectDistrictAttorneys = $row->nomIdx;
        } else {
          $strSelectDistrictAttorneys = '';
        }

        $attributes = array(
            // Nom de l'enquête
            $this->obj->getField(self::FIELD_NOM_ENQUETE),
            // Select pour premier enquêteur
            '',
            // Select pour DA
            $strSelectDistrictAttorneys,
            // Résumé des faits
            $this->obj->getField(self::FIELD_RESUME_FAITS),
            // Scène de crime
            $this->obj->getField(self::FIELD_DESC_SCENE_CRIME),
            // Rapports FCID
            $strRapportSID,
            // Autopsies
            $strRapportAutopsie,
            // Pistes & Démarches
            $this->obj->getField(self::FIELD_PISTES_DEMARCHES),
            // Enquêtes Personnalités
            '',
            // Témoins / Suspects
            '',
            // Chronologie
            '',
            // Notes diverses
            $this->obj->getField(self::FIELD_NOTES_DIVERSES),

            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
    
    public function getCopsAutopsies()
    {
        $attributes[self::SQL_WHERE_FILTERS] = array(
            self::FIELD_IDX_ENQUETE => $this->obj->getField(self::FIELD_IDX_ENQUETE),
        );
        return $this->objCopsAutopsieServices->getAutopsies($attributes);
    }

}
