<?php
namespace core\utils;

use core\interfaceimpl\ConstantsInterface;

/**
 * SessionUtils
 * @author Hugues
 * @since 1.23.06.21
 * @version v1.23.06.25
 */
class SessionUtils implements ConstantsInterface
{

    /**
     * @since 1.23.06.21
     * @version v1.23.06.25
     */
    public static function setSession(string $key, $value): void
    { $_SESSION[$key] = $value; }

    public static function unsetSession(string $key): void
    { unset($_SESSION[$key]); }

    /**
     * @since 1.23.06.21
     * @version v1.23.06.25
     */
    public static function fromServer(string $field): string
    {
        // Sanitize
        if (isset($_SERVER[$field])) {
            $strSanitized = htmlentities((string) $_SERVER[$field], ENT_QUOTES, 'UTF-8');
        } else {
            $strSanitized = '';
        }
        return filter_var($strSanitized, FILTER_SANITIZE_URL);
    }

    /**
     * @since 1.23.06.21
     * @version v1.23.06.25
     */
    public static function fromPost(string $key, bool $isUrl=true): mixed
    {
        // Sanitize
        if (isset($_POST[$key])) {
            $strSanitized = htmlentities((string) $_POST[$key], ENT_QUOTES, 'UTF-8');
        } else {
            $strSanitized = '';
        }
        return $isUrl ? filter_var($strSanitized, FILTER_SANITIZE_URL) : $strSanitized;
    }

    /**
     * @since 1.23.06.21
     * @version v1.23.06.25
     */
    public static function fromGet(string $key): mixed
    {
        // Sanitize
        if (isset($_GET[$key])) {
            $strSanitized = htmlentities((string) $_GET[$key], ENT_QUOTES, 'UTF-8');
        } else {
            $strSanitized = '';
        }
        return filter_var($strSanitized, FILTER_SANITIZE_URL);
    }
}
