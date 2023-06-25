<?php
namespace core\bean;

use core\utils\HtmlUtils;
use core\utils\UrlUtils;

/**
 * Classe WpPageAdminProfileBean
 * @author Hugues
 * @since v1.23.06.20
 * @version v1.23.06.25
 */
class WpPageAdminProfileBean extends WpPageAdminBean
{
    /**
     * @since v1.23.06.20
     * @version v1.23.06.25
     */
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Construction du menu du profil
        $this->arrSubOnglets = [
            self::CST_PFL_IDENTITY    => [self::FIELD_LABEL => self::LABEL_IDENTITY],
            self::CST_PFL_ABILITIES   => [self::FIELD_LABEL => self::LABEL_ABILITIES],
            self::CST_PFL_SKILLS      => [self::FIELD_LABEL => self::LABEL_SKILLS],
            self::CST_PFL_EQUIPMENT   => [self::FIELD_LABEL => self::LABEL_EQUIPMENT],
            self::CST_PFL_CONTACTS    => [self::FIELD_LABEL => self::LABEL_CONTACTS],
            self::CST_PFL_BACKGROUND  => [self::FIELD_LABEL => self::LABEL_BACKGROUND]
        ];
        /////////////////////////////////////////
        $this->defaultSubOnglet = self::CST_PFL_IDENTITY;

        /////////////////////////////////////////
        // Ajout du BreadCrumb
        $this->urlAttributes[self::CST_ONGLET] = self::ONGLET_PROFILE;
        $buttonContent = HtmlUtils::getLink(
            self::LABEL_PROFILE,
            UrlUtils::getPublicUrl($this->urlAttributes),
            self::CST_TEXT_WHITE
        );
        $this->breadCrumbsContent .= HtmlUtils::getButton($buttonContent, [self::ATTR_CLASS=>' '.self::BTS_BTN_DARK]);
        /////////////////////////////////////////
    }
     
    /**
     * @since v1.23.06.20
     * @version v1.23.06.25
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        return match ($slugSubContent) {
            self::CST_PFL_IDENTITY => new WpPageAdminProfileIdentityBean(),
            self::CST_PFL_ABILITIES => new WpPageAdminProfileAbilityBean(),
            self::CST_PFL_SKILLS => new WpPageAdminProfileSkillsBean(),
            //self::CST_MAIL_READ, self::CST_MAIL_WRITE => new WpPageAdminMailBean(),
            default => new WpPageAdminProfileIdentityBean(),
        };
    }

    public function getTabsBar(): string
    {
        /////////////////////////////////////////
        // Construction des onglets
        $strLis = '';
        foreach ($this->arrSubOnglets as $slugSubOnglet => $arrData) {
            $this->urlAttributes[self::CST_SUBONGLET] = $slugSubOnglet;
            $strIcon = '';

            if (!empty($arrData[self::FIELD_ICON])) {
                $strIcon = HtmlUtils::getIcon($arrData[self::FIELD_ICON]).self::CST_NBSP;
            }

            $blnActive = $this->slugSubOnglet==$slugSubOnglet;
            $blnActive |= $this->slugSubOnglet=='' && $slugSubOnglet==$this->defaultSubOnglet;
            $strLink = HtmlUtils::getLink(
                $strIcon.$arrData[self::FIELD_LABEL],
                UrlUtils::getPublicUrl($this->urlAttributes),
                self::NAV_LINK.' '.self::CST_TEXT_WHITE
            );
            $strLis .= $this->getBalise(
                self::TAG_LI,
                $strLink,
                [self::ATTR_CLASS=>self::NAV_ITEM.($blnActive ? ' btn-info' : ' '.self::BTS_BTN_DARK)]
            );
        }
        $attributes = [self::ATTR_CLASS=>implode(' ', [self::NAV, self::NAV_PILLS, self::NAV_FILL])];
        /////////////////////////////////////////

        return $this->getBalise(self::TAG_UL, $strLis, $attributes);
    }

    
    
    /*


  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletBackground()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-background.php';
    $attributes = [
        // Id
        $this->CopsPlayer->getField(self::FIELD_ID),
        // Background
        $this->CopsPlayer->getField(self::FIELD_BACKGROUND),
        // A priori, plus rien après
        '',
        '',
        '',
        '',
        '',
        '',
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletContacts()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-contacts.php';
    // TODO : à implémenter
    $attributes = [
        '1', '2', '3', '4', '5', '6', '7', '8',
        '1', '2', '3', '4', '5', '6', '7', '11',
        '12', '3', '4', '5', '6', '17'
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

  /**
   * @since 1.22.04.29
   * @version 1.22.04.29
   * /
  public function getSubongletEquipment()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-section-profile-equipment.php';
    // TODO : à implémenter
    $attributes = [
        '1', '2', '3', '4', '5', '6', '7', '8',
        '1', '2', '3', '4', '5', '6', '7', '11',
        '12', '3', '4', '5', '6', '17'
    ];
    return $this->getRender($urlTemplate, $attributes);
  }

*/
}
