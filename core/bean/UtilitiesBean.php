<?php
namespace core\bean;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\LabelsInterface;
use core\interfaceimpl\UrlsInterface;

if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe UtilitiesBean
 * @author Hugues
 * @since 1.00.00
 * @version 1.00.00
 */
class UtilitiesBean implements ConstantsInterface, LabelsInterface, UrlsInterface
{
    public $arrFullMonths = [1=>'Janvier', 'Février', 'Mars',
        'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre',
        'Octobre', 'Novembre', 'Décembre'];
    public $arrShortMonths = [1=>'Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin',
        'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    public $arrFullDays = [0=>'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    public $arrShortDays = [0=>'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'];
    public $arrShortEnglishDays = [0=>'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * @return bool
     */
    public static function isLogged(): bool
    {
        return is_user_logged_in();
    }

    /**
     * @return bool
     */
    public static function isCopsLogged(): bool
    {
      // On va checker dans les variables de SESSION si les infos relatives à Cops y sont stockées.
      return (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='err_login');
    }

    /**
     * @return bool
     */
    public static function isCopsEditor(): bool
    {
      // On va checker dans les variables de SESSION si les infos relatives à Cops y sont stockées.
      return (isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='Guest');
    }

    /**
     * @return int
     */
    public static function getWpUserId(): int
    {
        return get_current_user_id();
    }

    /**
     * @param string
     * @return string
     */
    public static function getCopsDate(string $format): string
    {
        $strCopsDate = get_option(self::CST_CAL_COPSDATE);
        $h = substr((string) $strCopsDate, 0, 2);
        $i = substr((string) $strCopsDate, 3, 2);
        $s = substr((string) $strCopsDate, 6, 2);
        $his = substr((string) $strCopsDate, 0, 8);
        $d = substr((string) $strCopsDate, 9, 2);
        $m = substr((string) $strCopsDate, 12, 2);
        $y = substr((string) $strCopsDate, 15);
        $dmy = substr((string) $strCopsDate, 9);
        $tsCops = mktime($h, $i, $s, $m, $d, $y);
        $objUtilitiesBean = new UtilitiesBean();

        switch ($format) {
            case self::FORMAT_STRJOUR :
                $strJour = $objUtilitiesBean->arrFullDays[date('N', $tsCops)];
                $attributes = [$strJour, $d, $objUtilitiesBean->arrFullMonths[$m*1], $y];
                $formatted = implode(' ', $attributes);
                break;
            case self::FORMAT_SIDEBAR_DATE :
                $formatted = $objUtilitiesBean->arrShortDays[date('N', $tsCops)].' '.$dmy.'<br>'.$his;
                break;
            case self::FORMAT_TS_NOW :
                $formatted = mktime($h, $i, $s, $m, $d, $y);
                break;
            case self::FORMAT_TS_START_DAY :
                $formatted = mktime(0, 0, 0, $m, $d, $y);
                break;
            case self::FORMAT_DATE_HIS    :
            case self::FORMAT_DATE_DMDY   :
            case self::FORMAT_DATE_YMD    :
            case self::FORMAT_DATE_MDY    :
            case self::FORMAT_DATE_DMY    :
            case self::FORMAT_DATE_YMDHIS :
                $formatted = date($format, mktime($h, $i, $s, $m, $d, $y));
                break;
            default :
                $formatted = $format;
                break;
        }
        return $formatted;
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function getExtraAttributesString(array $attributes): string
    {
        $extraAttributes = '';
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $extraAttributes .= ' '.$key.'-'.$subkey.'="'.$subvalue.'"';
                    }
                } else {
                    $extraAttributes .= ' '.$key.'="'.$value.'"';
                }
            }
        }
        return $extraAttributes;
    }

    /**
     * @param string $balise
     * @param string $label
     * @param array $attributes
     * @return string
     */
    public function getBalise(string $balise, string $label='', array $attributes=[]): string
    {
        return '<'.$balise.$this->getExtraAttributesString($attributes).'>'.$label.'</'.$balise.'>';
    }

    /**
     * @param string $urlTemplate
     * @param array $args
     * @return string
     */
    public function getRender(string $urlTemplate, array $args=[]): string
    {
        if (file_exists(PLUGIN_PATH.$urlTemplate)) {
            return vsprintf(file_get_contents(PLUGIN_PATH.$urlTemplate), $args);
        } else {
            $msgError = 'Fichier '.$urlTemplate
                . ' introuvable.<br>Vérifier le chemin ou la présence.';
            throw new \Exception($msgError);
        }
    }

    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getButton(string $label, array $attributes=[]): string
    {
        $buttonAttributes = [self::ATTR_TYPE => self::TAG_BUTTON, self::ATTR_CLASS => 'btn btn-default btn-sm'];
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (!isset($buttonAttributes[$key])) {
                    $buttonAttributes[$key]  = $value;
                } elseif ($key==self::ATTR_CLASS) {
                    $buttonAttributes[$key] .= ' '.$value;
                } else {
                    // TODO
                }
            }
        }
        return $this->getBalise(self::TAG_BUTTON, $label, $buttonAttributes);
    }
    
    /**
     * @param string $label
     * @param string $value
     * @param boolean $blnChecked
     * @return string
     * @since v1.22.11.26
     * @version v1.22.11.26
     */
    public function getOption(string $label, string $value, bool $blnChecked=false): string
    {
        $attributes = [self::ATTR_VALUE => $value];
        if ($blnChecked) {
            $attributes[self::CST_CHECKED] = self::CST_CHECKED;
        }
        return $this->getBalise(self::TAG_OPTION, $label, $attributes);
    }
    
    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getTh(string $label, array $attributes=[]): string
    {
        $buttonAttributes = ['scope' => 'col'];
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $buttonAttributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_TH, $label, $buttonAttributes);
    }
    
    /**
     * @param string $label
     * @param string $href
     * @param string $classe
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getLink(string $label, string $href, string $classe, array $extraAttributes=[]): string
    {
        $attributes = [self::ATTR_HREF => $href, self::ATTR_CLASS => $classe];
        if (!empty($extraAttributes)) {
            foreach ($extraAttributes as $key => $value) {
                $attributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_A, $label, $attributes);
    }
    
    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     */
    public function getDiv(string $label, array $attributes=[]): string
    {
        $divAttributes = [];
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $divAttributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_DIV, $label, $divAttributes);
    }
    
    /**
     * @param string
     * @return string
     * @version 1.22.10.06
     */
    public function getIcon(string $tag, string $prefix='', string $label=''): string
    {
        $allowedTags = [
            self::I_ANGLE_LEFT,
            self::I_ANGLES_LEFT,
            self::I_ARROWS_ROTATE,
            self::I_BACKWARD,
            self::I_CARET_LEFT,
            self::I_CARET_RIGHT,
            self::I_CIRCLE,
            self::I_DATABASE,
            self::I_DESKTOP,
            self::I_DOWNLOAD,
            self::I_FILE_CATEGORY,
            self::I_FILE_OPENED,
            self::I_FILE_CLOSED,
            self::I_FILE_COLDED,
            self::I_FILE_CIRCLE_CHECK,
            self::I_FILE_CIRCLE_PLUS,
            self::I_FILE_CIRCLE_XMARK,
            'book',
            'box-archive',
            'calendar-days',
            'envelope',
            'inbox',
            'square-pen',
            'square-plus',
            'trash-alt',
        ];
        if ($prefix!='') {
            $prefix .= ' ';
        }
        $prefix .= 'fa-solid fa-'.(in_array($tag, $allowedTags) ? $tag : 'biohazard');

        return $this->getBalise(self::TAG_I, $label, [self::ATTR_CLASS=>$prefix]);
    }

    /**
     * @param string $id
     * @param string $default
     * @return mixed
     */
    public function initVar(string $id, mixed $default=''): mixed
    {
        $value = $this->fromPost($id);
        if ($value=='') {
            $value = $this->fromGet($id);
            if ($value=='') {
                $value = $default;
            }
        }
        return $value;
    }

    /**
     * @return string
     */
    public function getPublicHeader(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getPublicFooter(): string
    {
        $urlTemplate = self::WEB_PP_MAIN_FOOTER;

        $args = [
            // ajaxUrl - 1
            admin_url('admin-ajax.php'),
        ];
        return $this->getRender($urlTemplate, $args);
    }

    /**
     * Retourne une donnée du serveur après nettoyage
     * @param string $field
     * @return string
     * @since 1.23.02.18
     * @version 1.23.02.18
     */
    public static function fromServer(string $field): string
    {
        // Sanitize
        $strSanitized = htmlentities((string) $_SERVER[$field], ENT_QUOTES, 'UTF-8');
        return filter_var($strSanitized, FILTER_SANITIZE_URL);
    }

    public static function fromPost(string $key): mixed
    {
        // Sanitize
        $strSanitized = htmlentities((string) $_POST[$key], ENT_QUOTES, 'UTF-8');
        return filter_var($strSanitized, FILTER_SANITIZE_URL);
    }

    public static function fromGet(string $key): mixed
    {
        // Sanitize
        $strSanitized = htmlentities((string) $_GET[$key], ENT_QUOTES, 'UTF-8');
        return filter_var($strSanitized, FILTER_SANITIZE_URL);
    }

    /**
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function analyzeUri()
    {
        $uri = static::fromServer('REQUEST_URI');
        $pos = strpos((string) $uri, '?');
        if ($pos!==false) {
            $arrParams = explode('&amp;', substr((string) $uri, $pos+1, strlen((string) $uri)));
            if (!empty($arrParams)) {
                foreach ($arrParams as $param) {
                    [$key, $value] = explode('=', $param);
                    $this->urlParams[$key] = $value;
                }
            }
            $uri = substr((string) $uri, 0, $pos-1);
        }
        $pos = strpos((string) $uri, '#');
        if ($pos!==false) {
            $this->anchor = substr((string) $uri, $pos+1, strlen((string) $uri));
        }
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->urlParams[$key] = static::fromPost($key);
            }
        }
        return $uri;
    }
}
