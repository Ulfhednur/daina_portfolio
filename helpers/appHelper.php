<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\helpers;

use Random\RandomException;
use Yii;
use yii\db\Exception;

/**
 * Утилиты
 */
abstract class appHelper
{

    public static function isMenuActive($view, string $menuitem): bool
    {
        return mb_substr(str_replace(env('ADMIN_URL') . '/', '', $view->context->route), 0, mb_strlen($menuitem)) == $menuitem;
    }

}