<?php
namespace core\actions;

use core\bean\WpPageAdminTchatBean;
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
 * @version v1.23.08.12
 */
class CopsTchatActions extends LocalActions
{
    public $strFormatLastRefreshed = 'Y-m-d H:i:s';

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public static function dealWithStatic(): string
    {
        $ajaxAction = SessionUtils::fromPost(self::AJAX_ACTION);
        $obj = new CopsTchatActions();
        switch ($ajaxAction) {
            case 'tchat' :
                $arrReturned = [
                    $obj->sendTchat()
                ];
                $returned = '{"refresh": '.json_encode($arrReturned, JSON_THROW_ON_ERROR).'}';
                break;
            case 'refresh' :
                $arrReturned = [
                    $obj->refreshTchatContent(),
                    $obj->refreshTchatContact()
                ];
                $returned = '{"refresh": '.json_encode($arrReturned, JSON_THROW_ON_ERROR).'}';
                break;
            case 'checkNotif' :
                $returned = $obj->checkNotif();
                break;
            default :
                $returned = 'Erreur dans CopsTchatActions le $_POST['.self::AJAX_ACTION.'] : '.$ajaxAction;
                break;
        }
        return $returned;
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
     * @since v1.23.08.12
     */
    public function refreshTchatContact(): array
    {
        $objBean = new WpPageAdminTchatBean();
        $strContacts = $objBean->buildTchatContacts();
        return ["target" => "tchatContact", "type" => "replace", "content" => $strContacts];
    }

    /**
     * @since v1.23.08.12
     */
    public function refreshTchatContent(): array
    {
        $objBean = new WpPageAdminTchatBean();
        $objTchatServices = new CopsTchatServices();

        $startTs = SessionUtils::fromPost('refreshed', false);
        if ($startTs=='') {
            $startTs = DateUtils::getStrDate($this->strFormatLastRefreshed, time()-60*5);
        }
        $attributes = [self::FIELD_TIMESTAMP => $startTs];
        $strContent = $objBean->buildTchatContent($attributes, 'now');

        // On met à jour le statut de lastRefreshed
        $objPlayer = CopsPlayerServices::getCurrentPlayer();
        $objTchatStatut = $objTchatServices->getTchatStatus(1, $objPlayer->getField(self::FIELD_ID));
        $objTchatStatut->setField(
            self::FIELD_LAST_REFRESHED,
            DateUtils::getStrDate($this->strFormatLastRefreshed, time())
        );
        if ($objTchatStatut->getField(self::FIELD_ID)=='') {
            $objTchatServices->insertTchatStatus($objTchatStatut);
        } else {
            $objTchatServices->updateTchatStatus($objTchatStatut);
        }

        if ($strContent=='') {
            return [
                "target" => "toastContent",
                "content" => $this->getToastContent('info', 'Information', 'Aucun nouveau message à afficher')
            ];
        } else {
            return [
                "target" => "tchatDialog",
                "type" => "append",
                "content" => $strContent
            ];
        }

    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    public function sendTchat(): array
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
                $returned = $this->dealWithDamageRoll(implode(' ', $arrCmd));
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
     * @version v1.23.08.12
     */
    private function dealWithSkillRoll(array $arrCmd): array
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
                self::FIELD_TIMESTAMP => DateUtils::getStrDate($this->strFormatLastRefreshed, time()),
                self::FIELD_TEXTE => $msg,
            ];
        } else {
            $msg = '<span class="badge badge-danger">Erreur dans la saisie de test de compétences. Format attendu : /roll xCy [-b] [-n].</span>';
            $attributes = [
                self::FIELD_SALON_ID => 1,
                self::FIELD_TO_PID => $objPlayer->getField(),
                self::FIELD_FROM_PID => -1,
                self::FIELD_TIMESTAMP => DateUtils::getStrDate($this->strFormatLastRefreshed, time()),
                self::FIELD_TEXTE => $msg,
            ];
        }
        $objTchat = new CopsTchatClass($attributes);
        $objTchatServices = new CopsTchatServices();
        $objTchatServices->insertTchat($objTchat);
        return $this->refreshTchatContent();
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    private function dealWithLocRoll(): array
    {
        $msg = 'Impact confirmé, zone touchée : '.DiceUtils::rollLocalisation();

        $attributes = [
            self::FIELD_SALON_ID => 1,
            self::FIELD_FROM_PID => -1,
            self::FIELD_TIMESTAMP => DateUtils::getStrDate($this->strFormatLastRefreshed, time()),
            self::FIELD_TEXTE => $msg,
        ];

        $objTchat = new CopsTchatClass($attributes);
        $objTchatServices = new CopsTchatServices();
        $objTchatServices->insertTchat($objTchat);
        return $this->refreshTchatContent();
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    private function dealWithHelp(): array
    {
        $msg  = "Vous avez demandé de l'aide ? La voici.<br>";
        $msg .= "La liste des commandes existantes est la suivante :<br>";
        $msg .= " - Pour faire un jet de compétence.<br>";
        $msg .= "......../roll xDy [-b.] [-n.]<br>";
        $msg .= " - Pour faire un jet de localisation.<br>";
        $msg .= "......../loc<br>";
        $msg .= " - Pour afficher l'aide, même si visiblement, c'est maîtrisé.<br>";
        $msg .= "......../help [commande]<br>";
        $msg .= " - Pour faire un jet de dégât.<br>";
        $msg .= "......../dmg xD6 [+.]<br>";
        $msg .= " - WIP.<br>";
        $msg .= "......../join<br>";
        $msg .= " - WIP.<br>";
        $msg .= "......../list<br>";
        $msg .= " - WIP.<br>";
        $msg .= "......../alias<br>";
        $msg .= "D'autres trucs à rajouter ?";
        
        $attributes = [
            self::FIELD_SALON_ID => 1,
            self::FIELD_TO_PID => -1,
            self::FIELD_FROM_PID => -2,
            self::FIELD_TIMESTAMP => DateUtils::getStrDate($this->strFormatLastRefreshed, time()),
            self::FIELD_TEXTE => $msg,
        ];

        $objTchat = new CopsTchatClass($attributes);
        return [
            "target" => "tchatDialog",
            "type" => "append",
            "content" => $objTchat->getBean()->getTchatRow(),
        ];
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    private function dealWithDamageRoll(string $cmd): array
    {
        $nbDice = 1;
        $modif = '';
        $reroll = '';
        $strScore = '';
        $nbRolls = 0;

        // Format attendu : '/^([0-9]+)D6(\+[0-9]+)?( -r)?$/';
        $pattern = '/^(\d+)D6(\+\d+)?( -r)?$/';
        if (preg_match($pattern, $cmd, $matches)) {
            [$cmd, $nbDice, $modif, $reroll] = $matches;
            $total = 0;
            if ($nbDice=='') {
                $nbDice = 1;
            }
            for ($i=1; $i<=$nbDice; $i++) {
                do {
                    $score = rand(1, 6);
                    $nbRolls++;
                } while ($score==1 && $reroll==' -r');
                $strScore .= $score.', ';
                $total += $score;
            }
            if ($modif!='') {
                $total += (int)substr($modif, 1);
            }

            $msg = 'Machin a lancé '.$cmd.' et a obtenu '.$total.' ['.substr($strScore, 0, -2).'].';
        } else {
            $msg = 'Erreur dans le jet : '.$cmd.'. Format attendu : xD6[+y][ -r].';
        }
        
        $attributes = [
            self::FIELD_SALON_ID => 1,
            self::FIELD_FROM_PID => -1,
            self::FIELD_TIMESTAMP => DateUtils::getStrDate($this->strFormatLastRefreshed, time()),
            self::FIELD_TEXTE => $msg,
        ];

        $objTchat = new CopsTchatClass($attributes);
        $objTchatServices = new CopsTchatServices();
        $objTchatServices->insertTchat($objTchat);
        return $this->refreshTchatContent();
    }

    /**
     * @since v1.23.08.05
     * @version v1.23.08.12
     */
    private function dealWithTchatInput($msg): array
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

        return $this->refreshTchatContent();
    }
}
