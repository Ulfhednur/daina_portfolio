<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets\Frontend;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FrontendAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/Frontend/src';

    public $css = [
        'less/uikit.less',
        'less/uikit.theme.less',
        'css/site.css',
    ];
    public $js = [
        'js/uikit.min.js',
        'js/uikit-icons.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
