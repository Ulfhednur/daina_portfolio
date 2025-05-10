<?php
declare(strict_types=1);
namespace app\widgets;

use conquer\codemirror\CodemirrorAsset;
use conquer\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

class CodemirrorWidget extends \conquer\codemirror\CodemirrorWidget
{
    /**
     * Registers Assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        $settings = $this->settings;
        $assets = $this->assets;
        if ($this->preset) {
            $preset = $this->getPreset($this->preset);
            if (isset($preset['settings'])) {
                $settings = ArrayHelper::merge($preset['settings'], $settings);
            }
            if (isset($preset['assets'])) {
                $assets = ArrayHelper::merge($preset['assets'], $assets);
            }
        }
        $settings = Json::encode($settings);

        $instanceName = 'CM_'.preg_replace('/[^\w\d]/ius', '', $id);
        $js = "CodeMirror.instances = {};
            CodeMirror.instances.{$instanceName} = CodeMirror.fromTextArea(document.getElementById('$id'), $settings);
            document.getElementById('$id').dataset.codeMirror = '{$instanceName}';";
        $view->registerJs($js, $view::POS_END);
        CodemirrorAsset::register($this->view, $assets);
    }
}