<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets\uikit;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UikitAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/uikit/src';

    public $css = [
        'less/uikit.less',
    ];
    public $js = [
        'js/uikit.min.js',
        'js/uikit-icons.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
