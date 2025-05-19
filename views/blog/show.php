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

/**
 * @var Post         $item
 * @var yii\web\View $this
 */
$this->title = $item->seo_title ? $item->seo_title : $item->title;
if ($item->seo_description) {
    $this->params['meta_description'] = $item->seo_description;
}
$this->params['lang_path'] = ['blog/show', 'alias' => $item->alias];
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container">
        <div>
            <figure class="uk-inline">
                <img src="<?= $item->image->url ?>" alt="<?= $item->image->alt ?>">
                <figcaption
                        class="uk-overlay uk-overlay-primary uk-position-bottom uk-flex uk-flex-between uk-flex-middle">
                    <h1 class="uk-h1 uk-text-uppercase uk-margin-remove">
                        <?= $item->title ?>
                        <div class="uk-text-small uk-text-muted"><?= $item->subtitle ?></div>
                    </h1>
                    <h5 class="uk-h5 uk-margin-remove">
                        <span uk-icon="calendar"></span> <?= (new \DateTime($item->created_date))->format('d.m.Y H:i:s') ?>
                    </h5>
                </figcaption>
            </figure>
            <div><?= $item->description ?></div>
        </div>
    </div>
</section>
