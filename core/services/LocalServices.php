<?php
namespace core\services;

/**
 * Classe LocalServices
 * @author Hugues
 * @since 1.22.04.28
 * @version v1.23.07.29
 */
class LocalServices extends GlobalServices
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $objDao = null;

    /**
     * @version v1.23.08.12
     */
    public function buildOrderByAndOrderMultiple(
        array $attributes,
        string $defaultField,
        string $defaultWay,
        string &$orderBy,
        string &$order
    ): void
    {
        // On récupère le sens du tri, mais pourrait évoluer plus bas, si multi-colonnes
        $order = $attributes[self::SQL_ORDER] ?? $defaultWay;

        // Traitement spécifique pour gérer le tri multi-colonnes
        if (!isset($attributes[self::SQL_ORDER_BY])) {
            $orderBy = $defaultField;
        } elseif (is_array($attributes[self::SQL_ORDER_BY])) {
            $orderBy = '';
            while (!empty($attributes[self::SQL_ORDER_BY])) {
                $orderBy .= array_shift($attributes[self::SQL_ORDER_BY]).' ';
                $orderBy .= array_shift($attributes[self::SQL_ORDER]).', ';
            }
            $orderBy = substr($orderBy, 0, -2);
            $order = '';
        } else {
            $orderBy = $attributes[self::SQL_ORDER_BY];
        }
    }
}
