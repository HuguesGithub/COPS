<?php
namespace core\bean;

use core\domain\CopsSoleilClass;

/**
 * CopsSoleilBean
 * @author Hugues
 * @since v1.23.04.26
 * @version v1.23.06.18
 */
class CopsSoleilBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsSoleil = ($obj ?? new CopsSoleilClass());
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.06.18
     */
    public function getColumn(): string
    {
        // La nuit noire
        $nightColor = '#000000';
        // La première astro
        $amAstroColor = '#463D4C';
        $amNautikColor = '#72627C';
        $amCivilColor = '#E9AD88';
        $earlyDayColor = '#7A9EB6';
        $dayColor = '#77B5FE';
        $lateDayColor = '#CC763F';
        $pmCivilColor = '#E06632';
        $pmNautikColor = '#75423C';
        $pmAstroColor = '#331C1A';
        $culmineColor = '#EDBE4E';
        $delim6h = '#FFFFFF';

        // On se lance dans la construction de la colonne
        $this->strStyle = 'width: %s; position: absolute; top: %spx; height: %spx; background-color: %s;';
        $this->strWidth = '100%';
        $this->maxHeight = 288;

        /////////////////////////////////////////////////////////////////
        // On ajoute les différents blocs.
        $strColContent = '';
        $strMinuit = '00:00';
        $strTitleNuit = 'Nuit %s - %s.';
        $strTitleAube = 'Aube %s - %s.';
        $strTitleAurore = 'Aurore %s - %s.';
        $strTitleJour = 'Journée %s - %s.&#xA;Durée : %s';
        $strTitleCrepuscule = 'Crépucule %s - %s.';
        $posTop = 0;
        $height = 0;
        $margin = 30;

        // Le premier créneau de nuit noire
        $heureStart = $strMinuit;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_ASTRO_AM);
        $strTitle = sprintf($strTitleNuit, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $nightColor, $posTop, $height);

        // Le premier créneau de nuit astronomique
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_NAUTIK_AM);
        $strTitle = sprintf($strTitleNuit, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $amAstroColor, $posTop, $height);

        // Le premier créneau de nuit nautique
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_CIVIL_AM);
        $strTitle = sprintf($strTitleAube, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $amNautikColor, $posTop, $height);

        // Le premier créneau de nuit civil
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_LEVER);
        $strTitle = sprintf($strTitleAurore, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $amCivilColor, $posTop, $height);

        // La journée
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_COUCHER);
        $dureeJour = $this->objCopsSoleil->getField(self::FIELD_DUREE_JOUR);
        $strTitle  = sprintf($strTitleJour, $heureStart, $heureFin, $dureeJour);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $dayColor, $posTop, $height);

        // Le deuxième créneau de nuit civil
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_CIVIL_PM);
        $strTitle = sprintf($strTitleCrepuscule, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $pmCivilColor, $posTop, $height);

        // Le deuxième créneau de nuit nautique
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_NAUTIK_PM);
        $strTitle = sprintf($strTitleCrepuscule, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $pmNautikColor, $posTop, $height);

        // Le deuxième créneau de nuit astronomique
        $heureStart = $heureFin;
        $heureFin = $this->objCopsSoleil->getField(self::FIELD_HEURE_ASTRO_PM);
        $strTitle = sprintf($strTitleNuit, $heureStart, $heureFin);
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $pmAstroColor, $posTop, $height);

        // Le deuxième créneau de nuit noire
        $heureStart = $heureFin;
        $heureFin = '23:59';
        $nbMinutes = $this->getDureeMinutes($heureFin, $heureStart);
        $posTop += $height;
        $height = floor($this->maxHeight*$nbMinutes/1440);
        while ($posTop+$height<$this->maxHeight) {
            ++$height;
        }
        $attributes = [
            self::ATTR_STYLE => vsprintf($this->strStyle, [$this->strWidth, $posTop, $height, $nightColor]),
            self::ATTR_TITLE => sprintf($strTitleNuit, $heureStart, $heureFin),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        // Le point culminant
        $heureMilieu = $this->objCopsSoleil->getField(self::FIELD_HEURE_CULMINE);
        $heureFin = $this->getHoraireAjout($heureMilieu, $margin);
        $heureStart = $this->getHoraireAjout($heureMilieu, -1*$margin);
        $strTitle = 'Apogée '.$heureStart.' - '.$heureFin;
        $posTop = floor($this->maxHeight*$this->getDureeMinutes($heureStart, $strMinuit)/1440);
        $height = 0;
        $strColContent .= $this->buidBlocHoraire($heureStart, $heureFin, $strTitle, $culmineColor, $posTop, $height);
        /////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////
        // On trace les repères visuels pour 06:00, 12:00 et 18:00
        $posTop = floor($this->maxHeight*$this->getDureeMinutes('06:00', $strMinuit)/1440);
        $height = 1;
        $attributes = [
            self::ATTR_STYLE => vsprintf($this->strStyle, [$this->strWidth, $posTop, $height, $delim6h]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        $posTop = floor($this->maxHeight*$this->getDureeMinutes('12:00', $strMinuit)/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($this->strStyle, [$this->strWidth, $posTop, $height, $delim6h]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);

        $posTop = floor($this->maxHeight*$this->getDureeMinutes('18:00', $strMinuit)/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($this->strStyle, [$this->strWidth, $posTop, $height, $delim6h]),
        ];
        $strColContent .= $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);
        /////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////
        // C'est fini, on retourne la cellule
        return $strColContent;
    }

    /**
     * @since v1.23.04.27
     * @version v1.23.04.30
     */
    public function buidBlocHoraire(
        string $heureStart,
        string $heureFin,
        string $strTitle,
        string $color,
        string &$posTop,
        string &$height
    ): string {
        $nbMinutes = $this->getDureeMinutes($heureFin, $heureStart);
        $posTop += $height;
        $height = floor($this->maxHeight*$nbMinutes/1440);
        $attributes = [
            self::ATTR_STYLE => vsprintf($this->strStyle, [$this->strWidth, $posTop, $height, $color]),
            self::ATTR_TITLE => $strTitle,
        ];
        return $this->getBalise(self::TAG_SPAN, self::CST_NBSP, $attributes);
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
        return 60-$ms+$me+($he-$hs-1)*60;
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getHoraireAjout(string $heureStart, int $ajoutMinutes): string
    {
        [$h, $m] = explode(':', $heureStart);
        if ($ajoutMinutes>0) {
            while ($ajoutMinutes>=60) {
                ++$h;
                $ajoutMinutes -= 60;
            }
            $m += $ajoutMinutes;
            if ($m>=60) {
                $m -= 60;
                ++$h;
            }
        } elseif ($ajoutMinutes<0) {
            while ($ajoutMinutes<=-60) {
                --$h;
                $ajoutMinutes += 60;
            }
            $m += $ajoutMinutes;
            if ($m<0) {
                $m += 60;
                --$h;
            }

        } else {
            // TODO : on ne fait rien
        }
        return str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
    }
}
