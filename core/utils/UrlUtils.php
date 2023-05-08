<?php
namespace core\utils;

use core\interfaceimpl\ConstantsInterface;

/**
 * UrlUtils
 * @author Hugues
 * @since 1.23.04.29
 * @version v1.23.05.07
 */
class UrlUtils implements ConstantsInterface
{

    /**
     * @since v1.23.04.29
     * @version v1.23.04.30
     */
    public static function getAdminUrl(array $urlAttributes=[]): string
    {
        $urlRoot = '/wp-admin/admin.php?page=hj-cops/admin_manage.php';

        if (!empty($urlAttributes)) {
            foreach ($urlAttributes as $key => $value) {
                $urlRoot .= self::CST_AMP.$key.'='.$value;
            }
        }
        return $urlRoot;
    }
}
