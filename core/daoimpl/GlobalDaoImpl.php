<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/*
define ('_SQL_PARAMS_WHERE_', 'where');
define ('_SQL_PARAMS_REPLACE_', 'replace');
define ('_SQL_PARAMS_LIMIT_', '__limit__');
define ('_SQL_PARAMS_ORDERBY_', '__orderby__');
*/
/**
 * Classe GlobalDaoImpl
 * @author Hugues.
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class GlobalDaoImpl
{

  /**
   * Limitateur
   * @var string $limit
   *
  protected $limit = _SQL_PARAMS_LIMIT_;

  public function __construct() {}

  protected function log($file, $line, $sql) {
    global $globalLogMySQL;
    $strrpos = strrpos($file, '/');
    $globalLogMySQL .= "[<strong>".substr($file, $strrpos+1)."</strong> : l.$line] : $sql]<br>";
  }

  /**
   * Cr��, Edite, Supprime une Entr�e
   * @since 1.0.00
   *
  protected function createEditDeleteEntry($file, $line, $requete, $arrParams=array()) {
    $sql = MySQL::wpdbPrepare($requete, $arrParams);
    MySQL::wpdbQuery($sql);
  }

  /**
   * Effectue un Select
   * @since 1.0.00
   *
  protected function selectEntriesAndLogQuery($file, $line, $requete, $params=array(), $arrOptions=array()) {
    $sql = MySQL::wpdbPrepare($requete, $params[_SQL_PARAMS_WHERE_]);
    if ( !empty($params[_SQL_PARAMS_REPLACE_]) ) {
      $sql = str_replace(_SQL_PARAMS_LIMIT_, $params[_SQL_PARAMS_REPLACE_][_SQL_PARAMS_LIMIT_], $sql);
      $sql = str_replace(_SQL_PARAMS_ORDERBY_, $params[_SQL_PARAMS_REPLACE_][_SQL_PARAMS_ORDERBY_], $sql);
    }
    if ( !isset($options['plugins']) ) { $options['plugins']='zombicide'; }
    MyLogger::log(self::DEBUG, $sql, $arrOptions);
    return MySQL::wpdbSelect($sql);
  }

  public function selectEntry($file, $line, $arrParams) {
    $requete = $this->selectRequest.$this->fromRequest.$this->whereId;
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, $arrParams));
  }

  public function selectEntriesWithFilters($file, $line, $filters) {
    $requete = $this->selectRequest.$this->fromRequest.$this->whereFilters.$this->orderBy.$this->limit;
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, $filters));
  }

  /**
   * @param unknown $file
   * @param unknown $line
   * @param unknown $arrDelete
   *
  public function delete($file, $line, $arrDelete) {
    $requete = $this->delete.$this->fromRequest.$this->whereId;
    return $this->createEditDeleteEntry($file, $line, $requete, $arrDelete);
  }

  /**
   * @param unknown $file
   * @param unknown $line
   * @param unknown $arrDelete
   *
  public function deleteWithFilters($file, $line, $arrDelete) {
    $requete = $this->delete.$this->fromRequest.$this->whereFilters;
    return $this->createEditDeleteEntry($file, $line, $requete, $arrDelete);
  }

  /**
   * @param unknown $file
   * @param unknown $line
   * @param unknown $arrInsert
   *
  public function insert($file, $line, $arrInsert) {
    $requete = $this->insert;
    return $this->createEditDeleteEntry($file, $line, $requete, $arrInsert);
  }

  public function insertUpdate($file, $line, $arrInsert) {
    $requete = $this->insertUpdate;
    $arrMerged = array_merge($arrInsert, $arrInsert);
    return $this->createEditDeleteEntry($file, $line, $requete, $arrMerged);
  }

  /**
   * @param unknown $file
   * @param unknown $line
   * @param unknown $arrUpdate
   *
  public function update($file, $line, $arrUpdate) {
    $requete = $this->update.$this->whereId;
    return $this->createEditDeleteEntry($file, $line, $requete, $arrUpdate);
  }

  public function getSetValues($file, $line, $field, $isSet=TRUE) {
    global $globalLogMySQLAllowed;
    $requete = "SHOW COLUMNS ".$this->fromRequest."LIKE '$field';";
    if ( $globalLogMySQLAllowed ) { $this->log($file, $line, $requete); }
    $row = MySQL::wpdbSelect($requete);
    $set  = $row[0]->Type;
    if ( $isSet ) {
      $set  = substr($set,5,strlen($set)-7);
    } else {
      $set  = substr($set,6,strlen($set)-8);
    }
    return preg_split("/','/",$set);
  }

  public function getDistinctValues($file, $line, $field) {
    global $globalLogMySQLAllowed;
    $requete = 'SELECT DISTINCT('.$field.') '.$this->fromRequest.'ORDER BY '.$field.' ASC;';
    if ( $globalLogMySQLAllowed ) { $this->log($file, $line, $requete); }
    $rows = MySQL::wpdbSelect($requete);
    $arrValues = array();
    if ( !empty($rows) ) {
      foreach ( $rows as $row ) {
        $arrValues[] = $row->{$field};
      }
    }
    return $arrValues;
  }
  */
}
?>
