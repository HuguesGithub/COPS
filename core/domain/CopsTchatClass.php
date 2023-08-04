<?php
namespace core\domain;

use core\bean\CopsTchatBean;
use core\services\CopsPlayerServices;
use core\utils\DateUtils;

/**
 * Classe CopsTchatClass
 * @author Hugues
 * @since v1.23.08.05
 */
class CopsTchatClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $salonId;
    protected $fromPlayerId;
    protected $toPlayerId;
    protected $timestamp;
    protected $texte;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    /**
     * @since v1.23.08.05
     */
    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);
        $this->stringClass = 'core\domain\CopsTchatClass';
    }

    /**
     * @since v1.23.08.05
     */
    public static function convertElement($row): CopsTchatClass
    {
        return parent::convertRootElement(new CopsTchatClass(), $row);
    }

    /**
     * @since v1.23.08.05
     */
    public function getBean(): CopsTchatBean
    { return new CopsTchatBean($this); }

    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////

    /**
     * @since v1.23.08.05
     */
    public function checkFields(): bool
    {
        $blnOk = true;

        if ($this->toPlayerId=='') {
            $this->toPlayerId = 0;
        }
        if ($this->fromPlayerId=='') {
            $this->fromPlayerId = 0;
        }
        if ($this->salonId=='') {
            $this->salonId = 1;
        }
        if ($this->timestamp=='' || $this->timestamp==0) {
            $this->timestamp = DateUtils::getStrDate('Y-m-d H:i:s', time());
        }
        
        if ($this->texte=='') {
            $blnOk = false;
        }
        return $blnOk;
    }

    /**
     * @since v1.23.08.05
     */
    public function getSender(): CopsPlayerClass
    {
        if ($this->fromPlayerId>0) {
            $objServices = new CopsPlayerServices();
            $objPlayer = $objServices->getPlayer($this->fromPlayerId);
        } else {
            $objPlayer = new CopsPlayerClass();
            if ($this->fromPlayerId==-1) {
                $objPlayer->setField(self::FIELD_NOM, 'BotRoll');
            }
        }
        return $objPlayer;
    }
}
