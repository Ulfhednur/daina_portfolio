<?php

namespace app\widgets\Status;

use yii\bootstrap5\Widget;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */
class Status extends Widget
{

    /**
     * {@inheritdoc}
     */
    public function run(): void
    {
        $icon = $this->options['published'] ? 'check' : 'ban';
        $tooltip = $this->options['published'] ? 'Опубликовать' : 'Снять с публикации';
        $textClass = $this->options['published'] ? 'uk-text-success' : 'uk-text-danger';
        $textClass .= ' inline-publish-change';
        $status = $this->options['published'] ? 'true' : 'false';
        echo PHP_EOL . '<span uk-icon="'.$icon.'" uk-tooltip="'.$tooltip.'" class="'.$textClass.'" data-published="'.$status.'" data-row="'.$this->options['row'].'" ></span>';

    }
}
