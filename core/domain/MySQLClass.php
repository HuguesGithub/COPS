<?php
namespace core\domain;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MySQL
 * Classe d'accès à la base de données.
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class MySQLClass
{
    /**
     * Prepare une requête avant de l'exécuter
     * @param $requete
     * @param $args
     * @return Resource
     */
    public static function wpdbPrepare($requete, $args=[])
    {
        global $wpdb;
        return $wpdb->prepare($requete, $args);
    }
    /**
     * Exécute une requête
     * @param $requete
     * @return
     */
    public static function wpdbQuery($requete)
    {
        global $wpdb;
        return $wpdb->query($requete);
    }
    /**
     * Exécute un Select
     * @param $requete
     * @return ResultSet
     */
    public static function wpdbSelect($requete)
    {
        global $wpdb;
        return $wpdb->get_results($requete);
    }
    /**
     * Exécute un Update
     * @param $table
     * @param $data
     * @param $where
     * @return result
     */
    public static function wpdbUpdate($table, $data, $where)
    {
        global $wpdb;
        return $wpdb->update($table, $data, $where);
    }
    /**
     * Exécute un Insert
     * @param $table
     * @param $data
     * @return integer
     */
    public static function wpdbInsert($table, $data)
    {
        global $wpdb;
        $wpdb->insert($table, $data);
        return $wpdb->insert_id;
    }
    /**
     * Exécute un Replace
     * @param $table
     * @param $data
     */
    public static function wpdbReplace($table, $data)
    {
        global $wpdb;
        $wpdb->replace($table, $data);
    }
    /**
     * Exécute un delete
     * @param $table
     * @param $where
     * @return integer
     */
    public static function wpdbDelete($table, $where)
    {
        global $wpdb;
        $requete = "DELETE FROM $table WHERE $where";
        return $wpdb->query($requete);
    }
    /**
     * Retourne la dernière requête exécutée.
     * @return string
     */
    public static function wpdbLastQuery()
    {
        global $wpdb;
        return $wpdb->last_query;
    }

    /**
     * Retourne l'identifiant de la dernière insertion
     *
     * @return int
     */
    public static function getLastInsertId()
    {
        global $wpdb;
        return $wpdb->insert_id;
    }

}
