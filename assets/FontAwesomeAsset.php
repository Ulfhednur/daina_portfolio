<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */
class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/fortawesome/font-awesome';

    public $css = [
        'css/all.css',
    ];
    public $js = [
        'js/all.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
