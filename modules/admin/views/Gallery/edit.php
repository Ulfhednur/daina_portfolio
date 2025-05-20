<?php
declare(strict_types=1);

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\assets\Admin\AdminAsset;
use app\assets\DateTimePicker\DateTimePickerAsset;
use app\assets\HtmlEditor\HtmlEditorAsset;
use app\models\Gallery;
use app\widgets\AdminToolbar\EditToolbar;
use app\widgets\ImageSelector\ImageSelector;
use app\widgets\Status\StatusSwitcher;
use app\widgets\TemplateSelector\GalleryTemplateSelector;
use yii\widgets\ActiveForm;

/** @var Gallery $item */

HtmlEditorAsset::register($this);
AdminAsset::register($this);
DateTimePickerAsset::register($this);
?>
<?php $form = ActiveForm::begin([
    'id' => 'admin-form',
    'action' => '/' . env('ADMIN_URL') . '/gallery',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'inputOptions' => ['class' => 'uk-input'],
        'errorOptions' => ['class' => 'uk-text-danger'],
    ],
]); ?>

<?php print EditToolbar::widget([
    'options' => [
        'item_id' => $item->id,
        'galleryId' => $item->id,
        'media' => !empty($item->id),
        'button-text' => 'Добавить фото'
    ],
]) ?>
<div class="uk-container uk-container-expand">
    <ul uk-tab>
        <li class="uk-active"><a href="">Параметры</a></li>
        <li><a href="">Фотографии</a></li>
    </ul>
    <div class="uk-switcher uk-margin">
        <div>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-3-4">
                    <ul id="lang-switcher" uk-tab>
                        <li class="uk-active"><a href="#">Русский</a></li>
                        <li id="en-tab"><a href="#">English</a></li>
                    </ul>
                    <div class="uk-switcher">
                        <div>
                            <?php foreach (['title', 'subtitle'] as $attributeName) { ?>
                                <?= $form->field($item, $attributeName)->textInput([
                                    'autofocus' => $attributeName == 'title',
                                ]) ?>
                            <?php } ?>

                            <?= $form->field($item, 'description')->textarea([
                                'id' => 'description_ru',
                            ]); ?>

                            <?php foreach (['seo_title', 'seo_description'] as $attributeName) { ?>
                                <?= $form->field($item, $attributeName)->textInput() ?>
                            <?php } ?>
                        </div>
                        <div>
                            <?php foreach (['title_en', 'subtitle_en'] as $attributeName) { ?>
                                <?= $form->field($item, $attributeName)->textInput() ?>
                            <?php } ?>

                            <?= $form->field($item, 'description_en')->textarea([
                                'id' => 'description_en',
                            ]); ?>

                            <?php foreach (['seo_title_en', 'seo_description_en'] as $attributeName) { ?>
                                <?= $form->field($item, $attributeName)->textInput() ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-4">
                    <?= $form->field($item, 'alias')->textInput() ?>
                    <?= StatusSwitcher::widget([
                        'status' => (bool)$item->published,
                    ]); ?>
                    <?= $form->field($item, 'image_id')->widget(ImageSelector::class, [
                        'selectType' => ImageSelector::SELECT_SINGLE,
                        'buttonText' => 'Выбрать изображение',
                    ]); ?>
                    <?= GalleryTemplateSelector::widget([
                        'name' => 'settings[template]',
                        'value' => !empty($item->settings['template']) ? $item->settings['template'] : GalleryTemplateSelector::TMPL_MASONRY,
                    ]) ?>
                </div>
            </div>
        </div>
        <div>
            <div class="uk-grid-small uk-grid-divider" uk-grid>
                <div id="gallery-selected-photos" class="uk-width-5-6">
                    <?= $this->render('@adminViews/Gallery/layouts/photos.php', ['items' => $item->media]); ?>
                </div>
                <div class="uk-width-1-6" id="image-edit-panel">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
<?= $form->field($item, 'id')->hiddenInput(['id' => 'item-id'])->label(false); ?>
<?php ActiveForm::end(); ?>

