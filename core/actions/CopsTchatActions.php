<?php
namespace core\actions;

use core\domain\CopsTchatClass;
use core\services\CopsPlayerServices;
use core\services\CopsTchatServices;
use core\utils\DateUtils;
use core\utils\DiceUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;

/**
 * CopsTchatActions
 * @author Hugues
 * @since v1.23.08.05
 */
class CopsTchatActions extends LocalActions
{
    /**
     * @since v1.23.08.05
     */
    public static function dealWithStatic(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        $obj = new CopsTchatActions();
        return match ($ajaxAction) {
            'tchat' => $obj->sendTchat(),
            'refresh' => $obj->refreshTchat(),
            'checkNotif' => $obj->checkNotif(),
            default => 'Erreur dans CopsTchatActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction,
        };
    }

    /**
     * @since v1.23.08.05
     */
    public function checkNotif(): string
    {
        $objTchatServices = new CopsTchatServices();
        $objsTchat = $objTchatServices->getTchats([], 'now');
        $nb = count($objsTchat);
        if ($nb!=0) {
            $spanAttributes = [self::ATTR_CLASS => 'badge badge-warning navbar-badge'];
            if ($nb>9) {
                $nb = '9<sup>+</sup>';
            }
            $returned = HtmlUtils::getBalise(self::TAG_SPAN, $nb, $spanAttributes);
        } else {
            $returned = '';
        }
        return '{"comment": '.json_encode($returned, JSON_THROW_ON_ERROR).'}';
    }

    /**
     * @since v1.23.08.05
     */
    public function refreshTchat(): string
    {
        $objTchatServices = new CopsTchatServices();
        $startTs = SessionUtils::fromPost('refreshed', false);
        if ($startTs=='') {
            $startTs = DateUtils::getStrDate('Y-m-d H:i:s', time()-60*5);
        }
        $attributes = [
            self::SQL_WHERE_FILTERS => [
                self::FIELD_TIMESTAMP => $startTs
            ]
        ];
        $objsTchat = $objTchatServices->getTchats($attributes, 'now');

        // On met à jour le statut de lastRefreshed
        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        $objTchatStatut = $objTchatServices->getTchatStatus(1, $objPlayer->getField(self::FIELD_ID));
        $objTchatStatut->setField(self::FIELD_LAST_REFRESHED, DateUtils::getStrDate('Y-m-d H:i:s', time()));
        if ($objTchatStatut->getField(self::FIELD_ID)=='') {
            $objTchatServices->insertTchatStatus($objTchatStatut);
        } else {
            $objTchatServices->updateTchatStatus($objTchatStatut);
        }

        if (!empty($objsTchat)) {
            $strRefreshTchat = '';
            while (!empty($objsTchat)) {
                $objTchat = array_shift($objsTchat);
                $strRefreshTchat .= $objTchat->getBean()->getTchatRow();
            }
            return '{"tchatContent": '.json_encode($strRefreshTchat, JSON_THROW_ON_ERROR).'}';
        } else {
            return $this->getToastContentJson(
                'info',
                'Information',
                'Aucun nouveau message à afficher'
            );
        }
    }

    /**
     * @since v1.23.08.05
     */
    public function sendTchat(): string
    {
        // On peut faire plein de choses via le Tchat. Enfin... pas encore, mais bientôt !!
        // On peut faire un jet de compétence : /roll 3C5 -b -n
        // On peut faire un jet de localisation : /loc
        // On peut faire un jet de dégâts : /dmg 1D6+3 -r
        // On doit pouvoir faire appel à la commande classique /help
        // On peut juste saisir un message : ........
        // Le reste, à suivre
        $val = SessionUtils::fromPost(self::ATTR_VALUE, false);
        $arrCmd = explode(' ', $val);
        $cmd = array_shift($arrCmd);
        switch ($cmd) {
            case '/roll' :
                $returned = $this->dealWithSkillRoll($arrCmd);
                break;
            case '/loc' :
                $returned = $this->dealWithLocRoll();
                break;
            case '/help' :
                $returned = $this->dealWithHelp();
                break;
            case '/dmg' :
                $returned = $this->dealWithDamageRoll($arrCmd);
                break;
            default :
                $returned = $this->dealWithTchatInput($val);
                break;
        }
        return $returned;
    }

    /**
     * @since v1.23.08.05
     */
    private function checkSkillRoll(
        string $roll,
        array $arrCmd,
        int $nbDice,
        int $seuil,
        int &$nbBlueDice=0,
        int &$nbBlackDice=0,
        string &$msg=''): bool
    {
        $blnOk = true;
        $strFormatAttendu = '(xCy [-b] [-n[Z]])';
        
        if (!is_numeric($nbDice) || !is_numeric($seuil)) {
            $msg   = '<strong>'.$roll.'</strong> ne correspond pas au format attendu '.$strFormatAttendu.'.';
            return false;
        }
        
        if (!empty($arrCmd)) {
            foreach ($arrCmd as $option) {
                if (substr($option, 0, 2)=='-b') {
                    $nbBlueDice = substr($option, 2) ? (int)substr($option, 2) : 1;
                } elseif (substr($option, 0, 2)=='-n') {
                    $nbBlackDice = substr($option, 2) ? (int)substr($option, 2) : 1;
                } else {
                    $msg   = '<strong>'.$option.'</strong>, option inconnue. Format attendu '.$strFormatAttendu.'.';
                    $blnOk = false;
                }
            }
        }

        return $blnOk;
    }

    /**
     * @since v1.23.08.05
     */
    private function dealWithSkillRoll(array $arrCmd): string
    {
        // Format attendu : 3C5 -b -n
        // L'option -b indique la présence d'un dé BLEU
        // L'option -n indique la présence d'un dé NOIR
        // L'option -nX indique la présence de X dés NOIRS (-n <=> -n1)
        $svgRoll = implode(' ', $arrCmd);
        $roll = array_shift($arrCmd);
        $posC = strpos($roll, 'C');
        $nbDice = (int)substr($roll, 0, $posC);
        $seuil  = (int)substr($roll, $posC+1);
        $nbBlueDice = 0;
        $nbBlackDice = 0;
        $nbSucces = 0;
        $nbCritics = 0;
        $msg = '';

        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        if ($this->checkSkillRoll($roll, $arrCmd, $nbDice, $seuil, $nbBlueDice, $nbBlackDice, $msg)) {
            // On lance les dés u'on stocke dans un tableau.
            $strResultat = '';
            for ($i=1; $i<=$nbDice; $i++) {
                if ($i<=$nbBlueDice) {
                    $color = 'b';
                } elseif ($i>$nbDice-$nbBlackDice) {
                    $color = 'n';
                } else {
                    $color = '';
                }
                $strResultat .= DiceUtils::rollSkill($seuil, $nbSucces, $nbCritics, $seuil!=10, $color);
                if ($i<$nbDice) {
                    $strResultat .= ', ';
                }
            }

            $msg  = $objPlayer->getField(self::FIELD_NOM).' a lancé '.$svgRoll.' et a obtenu ';
            $msg .= ($nbSucces==0 ? 'aucun' : '<span class="deRed">'.$nbSucces.'</span>').' succés';
            if ($nbCritics!=0) {
                if ($nbCritics==1) {
                    $msg .= ' et 1 échec critique';
                } else {
                    $msg .= ' et '.$nbCritics.' échecs critiques';
                }
            }
            $msg .= ' : ['.$strResultat.']';

            $attributes = [
                self::FIELD_SALON_ID => 1,
                self::FIELD_FROM_PID => -1,
                self::FIELD_TIMESTAMP => DateUtils::getStrDate('Y-m-d H:i:s', time()),
                self::FIELD_TEXTE => $msg,
            ];
        } else {
            $msg = '<span class="badge badge-danger">Erreur dans la saisie de test de compétences. Format attendu : /roll xCy [-b] [-n].</span>';
            $attributes = [
                self::FIELD_SALON_ID => 1,
                self::FIELD_TO_PID => $objPlayer->getField(),
                self::FIELD_FROM_PID => -1,
                self::FIELD_TIMESTAMP => DateUtils::getStrDate('Y-m-d H:i:s', time()),
                self::FIELD_TEXTE => $msg,
            ];
        }
        $objTchat = new CopsTchatClass($attributes);
        $objTchatServices = new CopsTchatServices();
        $objTchatServices->insertTchat($objTchat);

        return $this->refreshTchat();
    }

    /**
     * @since v1.23.08.05
     */
    private function dealWithLocRoll(): string
    {
        // 1-2 : Jambes
        // 3-4 : Abdomen
        // 5-7 : Torse
        // 8-9 : Bras
        // 10 : Tête

        $returned = $this->getToastContentJson(
            'warning',
            'WIP',
            'Dév en cours de LocRoll'
        );
        return $returned;
    }

    /**
     * @since v1.23.08.05
     */
    private function dealWithHelp(): string
    {
        $returned = $this->getToastContentJson(
            'warning',
            'WIP',
            'Dév en cours de Help'
        );
        return $returned;
    }

    /**
     * @since v1.23.08.05
     */
    private function dealWithDamageRoll(array $arrCmd): string
    {
        // Format attendu : '/^([0-9]+)D(\+[0-9]+)?$/';
        $pattern = '/^([0-9]+)D(\+[0-9]+)?$/';
        
        $returned = $this->getToastContentJson(
            'warning',
            'WIP',
            'Dév en cours de DamageRoll'
        );
        return $returned;
    }

    /**
     * @since v1.23.08.05
     */
    private function dealWithTchatInput($msg): string
    {
        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        $attributes = [
            self::FIELD_SALON_ID => 1,
            self::FIELD_FROM_PID => $objPlayer->getField(self::FIELD_ID),
            self::FIELD_TEXTE => trim(stripslashes($msg)),
        ];
        $objTchat = new CopsTchatClass($attributes);
        if ($objTchat->checkFields()) {
            $objTchatServices = new CopsTchatServices();
            $objTchatServices->insertTchat($objTchat);
        }

        return $this->refreshTchat();
    }
}
