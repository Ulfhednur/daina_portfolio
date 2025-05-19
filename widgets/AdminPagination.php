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
                'class' => 'uk-flex uk-flex-center',
            ],
            'listOptions' => ['class' => 'uk-pagination'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'prevPageCssClass' => 'uk-pagination-previous',
            'prevPageLabel' => '<span uk-pagination-previous></span>',
            'nextPageCssClass' => 'uk-pagination-next',
            'nextPageLabel' => '<span uk-pagination-next></span>',
            'activePageCssClass' => 'uk-active',
            'disabledPageCssClass' => 'ul-disabled',
            'disabledListItemSubTagOptions' => [
                'tag' => 'span',
                'class' => '',
                'aria-disabled' => 'true',
            ],
            'firstPageLabel' => false,
            'lastPageLabel' => false,
            'hideOnSinglePage' => true,
        ]);
        echo PHP_EOL . Html::endTag('nav');
    }
}
