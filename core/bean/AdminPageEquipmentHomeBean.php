<?php
namespace core\bean;

use core\utils\DateUtils;
use core\utils\HtmlUtils;
use core\utils\SessionUtils;
use core\utils\UrlUtils;

/**
 * AdminPageEquipmentHomeBean
 * @author Hugues
 * @since v1.23.07.08
 * @version v1.23.08.12
 */
class AdminPageEquipmentHomeBean extends AdminPageEquipmentBean
{
    /**
     * @since v1.23.07.08
     * @version v1.23.08.12
     */
    public function getContentOnglet(): string
    {
        return parent::getContentOnglet();
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.22
     */
    public function buildBreadCrumbs(): void
    {
        parent::buildBreadCrumbs();

        $strLink = HtmlUtils::getLink(self::LABEL_HOME, UrlUtils::getAdminUrl($this->urlAttributes), 'mx-1');
        $this->strBreadcrumbs .= HtmlUtils::getBalise(
            self::TAG_LI,
            $strLink,
            [self::ATTR_CLASS=>$this->styleBreadCrumbs]
        );
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function getCard(): string
    {
        $attributes = [
        ];
        return $this->getRender(self::WEB_PA_EQUIPMENT_HOME, $attributes);
    }

    /**
     * @since v1.23.07.08
     * @version v1.23.07.15
     */
    public function dealWithGetActions(): void
    {
        // TODO : need to be implemented.
    }

}
