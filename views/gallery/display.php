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
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var Gallery[]    $items
 */
$this->params['lang_path'] = ['gallery/display'];
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container">
        <div class="uk-text-center">
            <h1 class="uk-h1 uk-text-uppercase"><?= Yii::t('app', 'Галереи') ?></h1>
        </div>
        <section class="uk-section">
            <div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
                <?php foreach ($items as $item) { ?>
                    <div class="gallery-item">
                        <div class="gallery-image-wrapper">
                            <a href="<?= Url::to(['gallery/show', 'alias' => $item->alias]) ?>"
                               class="gallery-image-link">
                                <img src="<?= $item->image->url_preview ?>" alt="<?= $item->title ?>">
                            </a>
                        </div>
                        <h3 class="uk-h3 uk-text-uppercase">
                            <a class="uk-display-block uk-width-1-1"
                               href="<?= Url::to(['gallery/show', 'alias' => $item->alias]) ?>"><?= $item->title ?>
                                <?php if (!empty($item->subtitle)) { ?>
                                    <div class="uk-text-small uk-text-muted"><?= $item->subtitle ?></div>
                                <?php } ?>
                            </a>
                        </h3>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
</section>
