<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets\HtmlEditor;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HtmlEditorAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/HtmlEditor/src';

    public $css = [
        'css/uikit.css',
        'css/htmleditor.css',
        'css/codemirror.css',
        'css/show-hint.css',
    ];

    public $js = [
        'js/codemirror.js',
        'js/uikit.js',
        'js/htmleditor.js',
    ];
    public $depends = [
            'yii\web\JqueryAsset',
        ];
}
