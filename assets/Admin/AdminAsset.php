<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets\admin;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/Admin';

    public $css = ['css/admin.css'];

    public $js = ['js/admin.js'];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\Uikit\UikitAsset',
        'app\assets\DateTimePicker\DateTimePickerAsset',
    ];
}
