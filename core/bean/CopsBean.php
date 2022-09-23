<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsBean
 * @author Hugues
 * @since 1.22.09.23
 * @version 1.22.09.23
 */
class CopsBean extends UtilitiesBean
{
    public function __construct()
    {
        $this->urlOnglet = '/admin/?'.self::CST_ONGLET.'=';
    }
}
