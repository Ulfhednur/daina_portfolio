<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets\DateTimePicker;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/DateTimePicker/src';

    public $js = ['js/jquery.datetimepicker.full.min.js'];

    public $css = ['css/jquery.datetimepicker.min.css'];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
