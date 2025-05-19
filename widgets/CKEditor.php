<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

namespace app\widgets;

use yii\helpers\ArrayHelper;

class CKEditor extends \mihaildev\ckeditor\CKEditor
{
    public function init()
    {
        if (array_key_exists('preset', $this->editorOptions)) {
            if ($this->editorOptions['preset'] == 'basic-mode') {
                $this->presetBasicMode();
                unset($this->editorOptions['preset']);
            }
        }
        parent::init();
    }

    private function presetBasicMode()
    {
        $options['height'] = 250;

        $options['toolbarGroups'] = [
            ['name' => 'undo'],
            ['name' => 'mode'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'colors'],
            ['name' => 'links', 'groups' => ['links', 'insert']],
            ['name' => 'others', 'groups' => ['others', 'about']],
        ];
        $options['removeButtons'] = 'Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe';
        $options['removePlugins'] = 'elementspath';
        $options['resize_enabled'] = false;


        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }
}