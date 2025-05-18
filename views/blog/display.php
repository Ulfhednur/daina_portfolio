<?php
declare(strict_types=1);

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\models\Post;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;

/**
 * @var Post[]     $items
 * @var Pagination $pagination
 */

?>
<section class="uk-section">
    <div class="uk-container">
        <div class="uk-text-center">
            <h1 class="uk-h1 uk-text-uppercase"><?= Yii::t('app', 'Заметки') ?></h1>
        </div>
        <section class="uk-section">
            <div>
                <?php foreach ($items as $key => $item) { ?>
                    <div class="post-item uk-card uk-card-body uk-card-default">
                        <div class="uk-grid-small" uk-grid>
                            <div class="post-image-wrapper uk-width-1-3@s uk-width-1-4@m">
                                <a href="/blog/<?= $item->alias ?>" class="post-image-link">
                                    <img src="<?= $item->image->url_preview ?>" alt="<?= $item->image->alt ?>">
                                </a>
                            </div>
                            <div class="post-intro-wrapper uk-width-2-3@s uk-width-3-4@m">
                                <h3 class="uk-card-title">
                                    <a class="uk-display-block uk-width-1-1" href="/blog/<?= $item->alias ?>">
                                        <?= $item->title ?>
                                        <?php if (!empty($item->subtitle)) { ?>
                                            <div class="uk-text-small uk-text-muted"><?= $item->subtitle ?></div>
                                        <?php } ?>
                                    </a>
                                </h3>
                                <h5 class="uk-h5">
                                    <span uk-icon="calendar"></span> <?= (new \DateTime($item->created_date))->format('d.m.Y H:i:s') ?>
                                </h5>
                                <?php if (!empty($item->description)) { ?>
                                    <?php if (mb_strpos($item->description, '<hr class="read-more" />') !== false) {
                                        $content = explode('<hr class="read-more" />', $item->description)[0];
                                    } else {
                                        $content = mb_substr($item->description, 0, 450);
                                        if (mb_substr($content, -1, 1) === ' ') {
                                            $content = mb_substr($content, 0, -1);
                                        }
                                        if (mb_strlen($content) < mb_strlen($item->description)) {
                                            $content .= '&hellip;';
                                        }
                                    } ?>
                                    <div class="post-preview-content">
                                        <?= $content ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($key + 1 < count($items)) { ?>
                        <hr class="uk-divider uk-divider-icon"/>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </div>
    <?= LinkPager::widget([
        'pagination' => $pagination,
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
    ]); ?>
</section>
