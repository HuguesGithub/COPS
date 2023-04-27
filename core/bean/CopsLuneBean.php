<?php
namespace core\bean;

use core\domain\CopsLuneClass;

/**
 * CopsLuneBean
 * @author Hugues
 * @since v1.23.04.27
 * @version v1.23.04.30
 */
class CopsLuneBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objLuneSoleil = ($obj ?? new CopsLuneClass());
    }

}
