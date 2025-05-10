<?php
declare(strict_types=1);

namespace app\widgets;


use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Widget;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */
class AdminPagination extends Widget
{

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo PHP_EOL . Html::beginTag('nav');
        echo LinkPager::widget([
            'pagination' => $this->options['pagination'],
            'options' => [
                'class' => 'pagination justify-content-center'
            ],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'disabled',
            'disabledListItemSubTagOptions' => [
                'tag' => 'a',
                'class' => 'page-link',
                'aria-disabled' => 'true'
            ],
            'firstPageLabel' => 'В начало',
            'lastPageLabel' => 'В конец',
            'hideOnSinglePage' => true,
        ]);
        echo PHP_EOL . Html::endTag('nav');
    }
}
