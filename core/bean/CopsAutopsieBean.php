<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsAutopsieBean
 * @author Hugues
 * @since 1.22.10.09
 * @version 1.22.10.17
 */
class CopsAutopsieBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = ($objStd==null ? new CopsAutopsie() : $objStd);
        $this->urlOnglet   .= self::ONGLET_AUTOPSIE;
        $this->urlSubOnglet = $this->urlOnglet . '&amp;' . self::CST_SUBONGLET . '=';
        $this->strNoRapportDisponible = 'Aucune autopsie disponible';
    }

    /**
     * @return string
     * @since 1.22.10.17
     * @version 1.22.10.17
     */
    public function getCopsAutopsieLi()
    {
        $data = unserialize($this->obj->getField(self::FIELD_DATA));
        $url = $this->urlSubOnglet.'read&amp;id='.$this->obj->getField(self::FIELD_ID);
        $attributesLi = [self::ATTR_HREF=>$url, self::ATTR_CLASS=>'text-white'];
        $labelLi = $this->getBalise(self::TAG_A, $data['numDossier'], $attributesLi);
        return $this->getBalise(self::TAG_LI, $labelLi);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCopsAutopsieRow()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-tr-autopsie-row.php';
        $id          = $this->obj->getField(self::FIELD_ID);
        $data        = unserialize($this->obj->getField(self::FIELD_DATA));

        $urlViewEdit = $this->urlSubOnglet . self::CST_ENQUETE_WRITE . '&amp;id=' . $id;
        $numDossier  = $data['numDossier'];
        $objCopsEnquete = $this->obj->getCopsEnquete();

        $attributes = [
            // Id
            $id,
            // Url édition
            $urlViewEdit,
            // Numéro de Dossier de l'autopsie
            $numDossier,
            // Nom de l'enquête
            $objCopsEnquete->getField(self::FIELD_NOM_ENQUETE),
        ];

        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.09.20
     * @version 1.22.10.04
     */
    public function getWriteAutopsieBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-autopsie-write.php';
        /////////////////////////////////////////
        // Construction du panneau de droite

        // On récupère les infos relatives à l'id.
        // On doit avoir les droits pour pouvoir éditer l'autopsie

        $this->data = unserialize($this->obj->getField(self::FIELD_DATA));

        $attributes = [
            // L'id de l'autopsie
            $this->obj->getField(self::FIELD_ID),
            // Le Numéro de dossier qui est répété sur la 2è page
            $this->data['numDossier'],
            // La Card du Dossier
            $this->getCardDossier(),
            // La Card du Médico-légal
            $this->getCardMedicoLegal(),
            // La Card de l'enquête
            $this->getCardEnquete(),
            // Photo
            '',
            // Constatations
            $this->data['constatations'],
            // La Card de l'ondotologie
            $this->getCardOdontologie(),
            // La Card du Signalement
            $this->getCardSignalement(),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCardSignalement()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-autopsie-signalement.php';

        /////////////////////////////////////////////
        // Corpulence
        $strCorpulence = '';
        $arrCorpulence = ['1' => 'Maigre', '2' => 'Mince', '3' => 'Moyenne', '4' => 'Forte', '5' => 'Athlétique'];
        foreach ($arrCorpulence as $value => $label) {
            $attributes = [self::ATTR_VALUE => $value];
            if ($value==$this->data['corpulence']) {
                $attributes['selected'] = self::CST_SELECTED;
            }
            $strCorpulence .= $this->getBalise(self::TAG_OPTION, $label, $attributes);
        }
        /////////////////////////////////////////////

        /////////////////////////////////////////////
        // Cheveux
        $strLongueurCheveux = '';
        $arrLongueurCheveux = ['1' => 'Longs', '2' => 'Courts', '3' => 'Calvitie', '4' => 'Chauve'];
        foreach ($arrLongueurCheveux as $value => $label) {
            $attributes = [self::ATTR_VALUE => $value];
            if ($value==$this->data['cheveux_longueur']) {
                $attributes['selected'] = self::CST_SELECTED;
            }
            $strLongueurCheveux .= $this->getBalise(self::TAG_OPTION, $label, $attributes);
        }
        /////////////////////////////////////////////
        $strCoiffureCheveux = '';
        $arrCoiffureCheveux = ['1' => 'Raides', '2' => 'Ondulés', '3' => 'Frisés'];
        foreach ($arrCoiffureCheveux as $value => $label) {
            $attributes = [self::ATTR_VALUE => $value];
            if ($value==$this->data['cheveux_coiffure']) {
                $attributes['selected'] = self::CST_SELECTED;
            }
            $strCoiffureCheveux .= $this->getBalise(self::TAG_OPTION, $label, $attributes);
        }
        /////////////////////////////////////////////

        $attributes = [
            // Général 1 à 4
            $this->data['sexe'],
            $this->data['ethnie'],
            $this->data['taille'],
            $this->data['poids'],
            // Corpulence 5
            $strCorpulence,
            // Yeux 6 à 10
            $this->data['yeux_couleur'],
            $this->data['yeux_particularite'],
            isset($this->data['lunettes']) ? ' '.self::CST_CHECKED : '',
            $this->data['formeLunettes'],
            isset($this->data['lentilles']) ? ' '.self::CST_CHECKED : '',
            // Cheveux 11 à 14
            $this->data['cheveux_couleur'],
            $this->data['cheveux_particularite'],
            $strLongueurCheveux,
            $strCoiffureCheveux,
            // Pilosité 15 à 18
            $this->data['pilosite_couleur'],
            $this->data['pilosite_particularite'],
            isset($this->data['barbe']) ? ' '.self::CST_CHECKED : '',
            isset($this->data['moustache']) ? ' '.self::CST_CHECKED : '',
            // Signes particuliers
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCardOdontologie()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-autopsie-odontologie.php';

        $strMaxilliaireG = '';
        $strMaxilliaireD = '';
        $strMandibuleG   = '';
        $strMandibuleD   = '';

        $strModele = '<input type="text" class="form-control col-1 offset-2 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">
                      <input type="text" class="form-control col-1 teeth text-center">';

        $strMaxilliaireG = $strModele;
        $strMaxilliaireD = $strModele;
        $strMandibuleG   = $strModele;
        $strMandibuleD   = $strModele;

        $attributes = [$strMaxilliaireG, $strMaxilliaireD, $strMandibuleG, $strMandibuleD];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCardDossier()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-autopsie-dossier.php';
        $attributes = [stripslashes((string) $this->data['numDossier']), stripslashes((string) $this->data['dateHeureExamen']), stripslashes((string) $this->data['praticiensMedicoLegaux']), stripslashes((string) $this->data['nomPrenomVictime']), stripslashes((string) $this->data['ageApparent']), stripslashes((string) $this->data['circDecouverte']), stripslashes((string) $this->data['dateHeureDeces'])];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCardMedicoLegal()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-autopsie-medicolegal.php';
        $attributes = [stripslashes((string) $this->data['poidsCoeur']), stripslashes((string) $this->data['poidsRate']), stripslashes((string) $this->data['poidsEncephale']), stripslashes((string) $this->data['poidsFoie']), stripslashes((string) $this->data['poidsPoumonG']), stripslashes((string) $this->data['poidsReinG']), stripslashes((string) $this->data['poidsPoumonD']), stripslashes((string) $this->data['poidsReinD']), stripslashes((string) $this->data['toxicologie']), stripslashes((string) $this->data['serologie']), stripslashes((string) $this->data['anapath'])];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.14
     * @version 1.22.10.14
     */
    public function getCardEnquete()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-card-autopsie-enquete.php';

        $objServices = new CopsEnqueteServices();
        $objsCopsEnquete = $objServices->getEnquetes([]);

        $strContent = '';
        while (!empty($objsCopsEnquete)) {
            $objCopsEnquete = array_shift($objsCopsEnquete);
            $attributes = [self::ATTR_VALUE => $objCopsEnquete->getField(self::FIELD_ID)];
            if ($objCopsEnquete->getField(self::FIELD_ID)==$this->obj->getField(self::FIELD_IDX_ENQUETE)) {
                $attributes['selected'] = 'selected';
            }
            $label = $objCopsEnquete->getField(self::FIELD_NOM_ENQUETE);
            $strContent .= $this->getBalise(self::TAG_OPTION, $label, $attributes);
        }

        $attributes = [$strContent];
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 1.22.10.06
     * @version 1.22.10.06
     *
    public function getReadEnqueteBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquete-read.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        // On récupère l'objet CopsEnquete en fonction de l'id.
        $strRapportSID = $this->strNoRapportDisponible;
        $strRapportAutopsie = $this->strNoRapportDisponible;

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
    */

}
