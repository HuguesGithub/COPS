<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsEnqueteBean
 * @author Hugues
 * @since 1.22.09.16
 * @version 1.22.09.21
 */
class CopsEnqueteBean extends LocalBean
{
    public function __construct($objStd=null)
    {
        $this->CopsEnquete = ($objStd==null ? new CopsEnquete() : $objStd);
        $this->urlOnglet    = '/admin?' . self::CST_ONGLET . '='. self::ONGLET_ENQUETE;
        $this->urlSubOnglet = $this->urlOnglet.'&amp;' . self::CST_SUBONGLET . '=';
    }

    /**
     * 
     * @return string
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function getCopsEnqueteRow()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-enquete-row.php';
        $id          = $this->CopsEnquete->getField(self::FIELD_ID);
        $intSince    = $this->CopsEnquete->getField(self::FIELD_DSTART);
        $intLast     = $this->CopsEnquete->getField(self::FIELD_DLAST);
        
        switch ($this->CopsEnquete->getField(self::FIELD_STATUT_ENQUETE)) {
            case self::CST_ENQUETE_CLOSED :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_READ;
                $strActionsPossibles = '';
                break;
            case self::CST_ENQUETE_COLDED :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_READ;
                $url = $this->urlSubOnglet . self::CST_FILE_OPENED;
                $strActionsPossibles  = '<a href="'.$url.'&amp;action='.self::CST_ENQUETE_OPENED.'&amp;id='.$id.'" class="text-white" title="Réouvrir l\'enquête"><i class="fa-solid fa-file-circle-plus"></i></a>';
                break;
            case self::CST_ENQUETE_OPENED :
            default :
                $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_WRITE;
                $url = $this->urlSubOnglet . self::CST_FILE_CLOSED;
                $strActionsPossibles  = '<a href="'.$url.'&amp;action='.self::CST_ENQUETE_CLOSED.'&amp;id='.$id.'" class="text-white" title="Transférer au District Attorney"><i class="fa-solid fa-file-circle-check"></i></a>';
                $url = $this->urlSubOnglet . self::CST_FILE_COLDED;
                $strActionsPossibles .= '&nbsp;<a href="'.$url.'&amp;action='.self::CST_ENQUETE_COLDED.'&amp;id='.$id.'" class="text-white" title="Classer l\'enquête"><i class="fa-solid fa-file-circle-xmark"></i></a>';
                break;
        }

        $attributes = array(
            // Id
            $id,
            // Url de vision / édition, selon le statut.
            $urlViewEdit.'&amp;id='.$id,
            // Nom de l'enquête
            $this->CopsEnquete->getField(self::FIELD_NOM_ENQUETE),
            // Date création
            $this->displayNiceDateSince($intSince),
            // Date dernière modification
            $this->displayNiceDateSince($intLast),
            // Actions possibles
            $strActionsPossibles,
        );
        
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * 
     * @param integer $intDate
     * @return string
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function displayNiceDateSince($intDate)
    {
       $tsNow = UtilitiesBean::getCopsDate('tsnow');
       $tsDiff = $tsNow-$intDate;
       $strReturned = '';
       if ($tsDiff<60) {
           $strReturned = "À l'instant";
       } elseif ($tsDiff<60*60) {
           $strReturned = "Il y a ".round($tsDiff/60)."min";
       } elseif ($tsDiff<60*60*24) {
           $strReturned = "Il y a ".round($tsDiff/(60*60))."hrs";
       } else {
           $strReturned = "Il y a ".round($tsDiff/(60*60*24))."j";
       }
       return $strReturned;
    }

    /**
     * @return string
     * @since 1.22.09.20
     * @version 1.22.09.21
     */
    public function getWriteEnqueteBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquete-write.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        // Gestion d'édition (création ou modification) d'un dossier d'enquête
        // On récupère l'objet CopsEnquete en fonction de l'id.
        // Attention, si CopsEnquete n'est pas ouvert, on doit rediriger vers une simple vision.
        
        $attributes = array(
            // Id de l'enquête, s'il existe
            $this->CopsEnquete->getField(self::FIELD_ID),
            // Url pour Annuler
            '/admin?onglet=enquete',
            // Nom de l'enquête
            $this->CopsEnquete->getField(self::FIELD_NOM_ENQUETE),
            // Select pour premier enquêteur
            '',
            // Select pour DA
            '',
            // Résumé des faits
            $this->CopsEnquete->getField(self::FIELD_RESUME_FAITS),
            // Scène de crime
            $this->CopsEnquete->getField(self::FIELD_DESC_SCENE_CRIME),
            // Rapports FCID
            '', 
            // Autopsies
            '', 
            // Pistes & Démarches
            $this->CopsEnquete->getField(self::FIELD_PISTES_DEMARCHES),
            // Notes diverses
            $this->CopsEnquete->getField(self::FIELD_NOTES_DIVERSES),
            // TODO :
            // Enquêtes Personnalités
            // Témoins / Suspects
            // Chronologie
            
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
    
    public function getReadEnqueteBlock()
    {
        return '';
    }
    
}
