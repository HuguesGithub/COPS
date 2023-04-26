<?php
namespace core\bean;

use core\domain\CopsSoleilClass;

/**
 * CopsSoleilBean
 * @author Hugues
 * @since v1.23.04.26
 * @version v1.23.04.30
 */
class CopsSoleilBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsSoleil = ($obj ?? new CopsSoleilClass());
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getColumn(): string
    {
        // TODO : définir une colonne pour une journée.
        $nightColor = '#000000';
        $nauticColor = '#';
        $civilColor = '#';
        $dayColor = '#FFFFFF';
        $culmineColor = '#';

        // Pour le moment, on n'a que heureLever et heureCoucher
        // Au format HH:ii
        // On calcul la durée en minutes de la nuit du matin,
        // puis la durée de la journée,
        // enfin la durée de la nuit du soir.
        // On peut ainsi déterminer la hauteur de chaque bloc de données par rapport à 1440.
        // Ainsi que le position absolue dans le cadre
        // On retourne des <td>...</td>

        // TODO : enrichir la présentation quand la table aura gagné des colonnes.
    // On va afficher les données de la table wp_7_cops_soleil.
    // Les données seront affichées par semaine, celle comprenant la date du jour ingame.
    // Le moment de la journée sera représenté visuellement en couleur.
    // Noir : période entre le crépuscule astronomique
    // Bleu-Noir : période du crépuscule nautique
    // Bleu-Jaune : période du crépuscule civil
    // Jaune : période de la journée.
    // Orange : période autour de l'heure culminante +- 30 minutes ?

    // En title, elles doivent avoir les infos pertinentes.
    // En survol sur les différentes zones, la date, le nom de la période et les horaires extrêmes.

        // On se lance dans la construction de la colonne
        $strColContent = '';
        $strStyle = 'position: absolute; top: %spx; height: %spx; background-color: %s;';

        // La première nuit de la journée
        $heureLever = $this->objCopsSoleil->getField(self::FIELD_HEURE_LEVER);
        $lengthNuit = $this->getDureeMinutes($heureLever, '00:00');
        $posNuit = 0;
        $heightNuit = floor(100*$lengthNuit/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($strStyle, [$posNuit, $heightNuit, $nightColor]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        // La journée
        $heureCoucher = $this->objCopsSoleil->getField(self::FIELD_HEURE_COUCHER);
        $lengthJour = $this->getDureeMinutes($heureCoucher, $heureLever);
        $posJour = $posNuit + $heightNuit;
        $heightJour = floor(100*$lengthJour/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($strStyle, [$posJour, $heightJour, $dayColor]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        // La deuxième nuit de la journée
        $lengthNuit2 = $this->getDureeMinutes('23:59', $heureCoucher);
        $posNuit2 = $posJour + $heightJour;
        $heightNuit2 = floor(100*$lengthNuit2/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($strStyle, [$posNuit2, $heightNuit2, $nightColor]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        $attributes = [
            self::ATTR_STYLE => 'position: relative; height: 100%;',
        ];
        return $this->getBalise(self::TAG_TD, $strColContent, $attributes);
    }

    /**
     * Retourne le nombre de minutes entre $end et $start avec $end>$start.
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getDureeMinutes(string $end, string $start): int
    {
        // Les deux horaires sont sous le format hh:ii.
        [$he, $me] = explode(':', $end);
        [$hs, $ms] = explode(':', $start);
        return 60-$ms+$me+($hs-$he-1)*60;
    }

}
