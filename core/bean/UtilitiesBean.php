<?php
namespace core\bean;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\FieldsInterface;
use core\interfaceimpl\LabelsInterface;
use core\interfaceimpl\UrlsInterface;
use core\utils\SessionUtils;

/**
 * Classe UtilitiesBean
 * @author Hugues
 * @since 1.00.00
 * @version v1.23.05.28
 */
class UtilitiesBean implements ConstantsInterface, LabelsInterface, UrlsInterface, FieldsInterface
{
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
     * @version v1.23.05.28
     */
    public static function isCopsLogged(): bool
    {
      // On va checker dans les variables de SESSION si les infos relatives à Cops y sont stockées.
      return isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='err_login';
    }

    /**
     * @version v1.23.05.28
     */
    public static function isCopsEditor(): bool
    {
      // On va checker dans les variables de SESSION si les infos relatives à Cops y sont stockées.
      return isset($_SESSION[self::FIELD_MATRICULE]) && $_SESSION[self::FIELD_MATRICULE]!='Guest';
    }

    /**
     * @return int
     */
    public static function getWpUserId(): int
    {
        return get_current_user_id();
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
     * @param string $id
     * @param string $default
     * @return mixed
     */
    public function initVar(string $id, mixed $default=''): mixed
    {
        $value = SessionUtils::fromPost($id);
        if ($value=='') {
            $value = SessionUtils::fromGet($id);
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
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.18
     */
    public function analyzeUri()
    {
        $uri = SessionUtils::fromServer('REQUEST_URI');
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
                $this->urlParams[$key] = SessionUtils::fromPost($key);
            }
        }
        return $uri;
    }
}
