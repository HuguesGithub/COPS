<?php
namespace core\bean;

use core\domain\CopsMeteoClass;
use core\utils\HtmlUtils;

/**
 * CopsMeteoBean
 * @author Hugues
 * @since 1.23.04.36
 * @version v1.23.06.18
 */
class CopsMeteoBean extends UtilitiesBean
{
    public function __construct($obj=null)
    {
        $this->objCopsMeteo = ($obj ?? new CopsMeteoClass());
    }

    /**
     * @since v1.23.05.15
     * @version v1.23.06.18
     */
    public function getTableRow(): TableauRowHtmlBean
    {
        $objRow = new TableauRowHtmlBean();
        //
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP, self::TAG_TH));
        //
        $heureMeteo = $this->objCopsMeteo->getField(self::FIELD_HEURE_METEO);
        $objRow->addCell(new TableauCellHtmlBean($heureMeteo, self::TAG_TH));
        // Construction découpée sinon trop longue.
        $wicon = $this->objCopsMeteo->getField(self::FIELD_WEATHER_ID);
        $strDiv = HtmlUtils::getDiv('', [self::ATTR_CLASS=>self::CST_WICON, self::ATTR_DATA_ICON=>$wicon]);
        $objRow->addCell(new TableauCellHtmlBean($strDiv, self::TAG_TD, self::STYLE_TEXT_CENTER));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_TEMPERATURE).'°C'));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_WEATHER)));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_FORCE_VENT).' km/h'));
        //
        $sensId = $this->objCopsMeteo->getField(self::FIELD_SENS_VENT);
        $strSpan = $this->getBalise(self::TAG_SPAN, '↑', [self::ATTR_CLASS=>'comp sa'.$sensId]);
        $objRow->addCell(new TableauCellHtmlBean($strSpan, self::TAG_TD, self::STYLE_TEXT_CENTER));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_HUMIDITE).'%'));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_BAROMETRE).' mbar'));
        //
        $objRow->addCell(new TableauCellHtmlBean($this->objCopsMeteo->getField(self::FIELD_VISIBILITE).' km'));
        //
        $objRow->addCell(new TableauCellHtmlBean(self::CST_NBSP));

        return $objRow;
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
