<?php
if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * CopsEnqueteChronologieBean
 * @author Hugues
 * @since 1.22.09.23
 * @version 1.22.09.23
 */
class CopsEnqueteChronologieBean extends CopsBean
{
    public function __construct($objStd=null)
    {
        parent::__construct();
        $this->obj          = ($objStd==null ? new CopsEnqueteChronologieBean() : $objStd);
    }
    
}
