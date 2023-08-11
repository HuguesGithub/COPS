<?php
namespace core\utils;

use core\interfaceimpl\ConstantsInterface;

/**
 * DiceUtils
 * @author Hugues
 * @since v1.23.08.12
 */
class DiceUtils implements ConstantsInterface
{
    /**
     * @param int $seuil Valeur à atteindre pour obtenir un succès.
     * @param string $color Couleur du dé lancé ['', b, n, w, r]
     * @param boolean $blnExplode Le dé peut-il exploser ?
     * Peut-être possible lors du premier dé lancé, mais pas ultérieurment.
     * @since v1.23.08.05
     */
    public static function rollSkill(
        int $seuil,
        int &$nbSucces=0,
        int &$nbCritics=0,
        bool $blnExplode=false,
        string $color=''
    ): string
    {
        $strResultat = '';

        $score = rand(1, 10);
        if ($score>=$seuil) {
            $nbSucces += $color=='b' ? 2 : 1;
            if ($score==10 && $blnExplode) {
                $somme = 10;
                $strResultat = '[<span class="dice deRed successRoll">10</span>';
                do {
                    $score = rand(1, 10);
                    $somme += $score;
                    if ($score>=$seuil) {
                        $nbSucces++;
                        $strResultat .= ', <span class="dice deRed successRoll">'.$score.'</span>';
                    } else {
                        $strResultat .= ', <span class="dice failRoll">'.$score.'</span>';
                    }
                } while ($score==10);
                $strResultat .= ']';
                $strResultat = '<span class="dice deRed successRoll">'.$somme.'</span> '.$strResultat.' ';
            } else {
                $strResultat .= '<span class="dice deRed successRoll">'.$score.'</span>';
            }
        } else {
            if ($color=='n') {
                $nbCritics++;
            }
            $strResultat .= '<span class="dice failRoll">'.$score.'</span>';
        }
        return $strResultat;
    }

    /**
     * @since v1.23.08.12
     */
    public static function rollLocalisation(): string
    {
        $score = rand(1, 10);
        // 1-2 : Jambes
        // 3-4 : Abdomen
        // 5-7 : Torse
        // 8-9 : Bras
        // 10 : Tête
        return match ($score) {
            1 => 'Jambe droite (-)',
            2 => 'Jambe gauche (-)',
            3, 4 => 'Abdomen (1)',
            5, 6, 7 => 'Torse (2)',
            8 => 'Bras gauche (-)',
            9 => 'Bras droit (-)',
            10 => 'Tête (3)',
        };
    }
}
