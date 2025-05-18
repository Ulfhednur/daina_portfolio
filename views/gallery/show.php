<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\models\Gallery;

/**
 * @var Gallery $item
 * @var yii\web\View $this
 */

$this->title = $item->seo_title ? $item->seo_title : $item->title;
if ($item->seo_description) {
    $this->params['meta_description'] = $item->seo_description;
}
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container">
        <div class="uk-text-center">
            <h1 class="uk-h1 uk-text-uppercase">
                <?=$item->title?>
                <div class="uk-text-small uk-text-muted"><?=$item->subtitle?></div>
            </h1>
            <div><?=$item->description?></div>
        </div>
        <section class="uk-section">
            <div class="uk-grid-small uk-child-width-1-4@m" uk-grid="masonry: pack" uk-lightbox="animation: slide">
                <?php foreach ($item->media as $media){ ?>
                    <div class="gallery-item uk-text-center">
                        <div class="gallery-image-wrapper">
                            <a href="<?=$media->url?>" class="gallery-image-link" data-caption="<?=$media->title?>">
                                <img src="<?=$media->url_preview?>" alt="<?=$media->alt?>">
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
</section>
