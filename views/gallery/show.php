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
use app\widgets\TemplateSelector\GalleryTemplateSelector;

/**
 * @var Gallery      $item
 * @var yii\web\View $this
 */

$this->title = $item->seo_title ? $item->seo_title : $item->title;
if ($item->seo_description) {
    $this->params['meta_description'] = $item->seo_description;
}
$this->params['lang_path'] = ['gallery/show', 'alias' => $item->alias];
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container uk-container-expand">
        <div class="uk-text-center">
            <h1 class="uk-h1 uk-text-uppercase">
                <?= $item->title ?>
                <div class="uk-text-small uk-text-muted"><?= $item->subtitle ?></div>
            </h1>
            <div><?= $item->description ?></div>
        </div>
        <section class="uk-section">
            <?php
                switch ($item->settings['template']) {
                    default:
                    case GalleryTemplateSelector::TMPL_MASONRY: ?>
                        <div class="uk-grid-small uk-child-width-1-4@m" uk-grid="masonry: pack" uk-lightbox="animation: slide">
                    <?php break; ?>
                    <?php case GalleryTemplateSelector::TMPL_JUSTIFIED: ?>
                        <div class="flex-gallery" uk-lightbox="animation: slide">
                    <?php break; ?>
                    <?php case GalleryTemplateSelector::TMPL_GRID: ?>
                        <div class="uk-grid-small uk-child-width-1-4@m" uk-grid uk-lightbox="animation: slide">
                    <?php break; ?>
                <?php } ?>
                <?php foreach ($item->media as $media) { ?>
                    <figure class="gallery-item uk-text-center" uk-scrollspy="cls:uk-animation-fade"
                                    style="--width: <?= $media->settings['dimensions']['width'] ?>;--height: <?= $media->settings['dimensions']['height'] ?>;']">
                        <a href="<?= $media->url ?>" class="gallery-image-link" data-caption="<?= $media->title ?>">
                            <img src="<?= $media->url_preview ?>" alt="<?= $media->alt ?>">
                        </a>
                    </figure>
                <?php } ?>
                </div>
        </section>
    </div>
</section>
