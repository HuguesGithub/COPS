<?php
namespace core\bean;

use core\domain\CopsMeteoClass;

/**
 * CopsMeteoBean
 * @author Hugues
 * @since 1.23.04.36
 * @version v1.23.04.30
 */
class CopsMeteoBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsMeteo = ($obj ?? new CopsMeteoClass());
    }

    /**
     * @since v1.23.04.26
     * @version v1.23.04.30
     */
    public function getAdminRow()
    {
        // Initialisation de la ligne de données
        $adminRow = '';
        $adminRow .= $this->getBalise(self::TAG_TH, self::CST_NBSP);

        // On ajoute les différents champs
        $adminRow .= $this->getBalise(self::TAG_TH, $this->objCopsMeteo->getField(self::FIELD_HEURE_METEO));
        // Construction découpée sinon trop longue.
        $wicon = $this->objCopsMeteo->getField(self::FIELD_WEATHER_ID);
        $strDiv = $this->getDiv('', [self::ATTR_CLASS=>self::CST_WICON, self::ATTR_DATA_ICON=>$wicon]);
        $adminRow .= $this->getBalise(self::TAG_TD, $strDiv);

        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_TEMPERATURE).'°C');
        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_WEATHER));
        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_FORCE_VENT).' km/h');

        $sensId = $this->objCopsMeteo->getField(self::FIELD_SENS_VENT);
        $strSpan = $this->getBalise(self::TAG_SPAN, '↑', [self::ATTR_CLASS=>'comp sa'.$sensId]);
        $adminRow .= $this->getBalise(self::TAG_TD, $strSpan);
        
        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_HUMIDITE).'%');
        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_BAROMETRE).' mbar');
        $adminRow .= $this->getBalise(self::TAG_TD, $this->objCopsMeteo->getField(self::FIELD_VISIBILITE).' km');

        $adminRow .= $this->getBalise(self::TAG_TH, self::CST_NBSP);
        // On retourne la ligne terminée
        return $this->getBalise(self::TAG_TR, $adminRow);
    }

    /**
     * Feels like temperature
val T = temperature.state as Number
val RH = humidity.state as Number

val HI = -42.379 + 2.04901523*T + 10.14333127*RH - .22475541*T*RH - .00683783*T*T - .05481717*RH*RH 
+ .00122874*T*T*RH + .00085282*T*RH*RH - .00000199*T*T*RH*RH

if (RH< 13 && T>= 80 && T<=110) {
   // ADJUSTMENT = [(13-RH)/4]*SQRT{[17-ABS(T-95.)]/17}
   var adjust = ((13-RH)/4)  * Math.sqrt(17-Math.abs(T-95.)/17)
   HI -= adjust
} else if (RH>85 && T>80 && T<87) {
   // ADJUSTMENT = [(RH-85)/10] * [(87-T)/5]
   var adjust = ((RH-85)/10) * ((87-T)/5)
   HI += adjust
} else if (T<80){
   // HI = 0.5 * {T + 61.0 + [(T-68.0)*1.2] + (RH*0.094)} 
   HI = 0.5 * (T + 61.0 + ((T-68.0)*1.2) + (RH*0.094))
}

// remember it is in F in Germany you would prefer to use C
// C to F =  T(°F) = T(°C) × 9/5 + 32
// F to C =  T(°C) = (T(°F) - 32) × 5/9
     * 
     */
}
