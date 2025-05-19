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
use app\models\Page;
use app\widgets\AdminToolbar\EditToolbar;
use app\widgets\ImageSelector\ImageSelector;
use app\widgets\Status\StatusSwitcher;
use yii\widgets\ActiveForm;

/** @var Page $item */

HtmlEditorAsset::register($this);
AdminAsset::register($this);
DateTimePickerAsset::register($this);
?>
<?php $form = ActiveForm::begin([
    'id' => 'admin-form',
    'action' => '/' . env('ADMIN_URL') . '/post',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'inputOptions' => ['class' => 'uk-input'],
        'errorOptions' => ['class' => 'uk-text-danger'],
    ],
]); ?>

<?php print EditToolbar::widget([
    'options' => ['item_id' => $item->id],
]) ?>
<div class="uk-container uk-container-expand">
    <div class="uk-grid-small" uk-grid>
        <div class="uk-width-3-4">
            <ul uk-tab>
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
                        <?= $form->field($item, $attributeName)->textInput([
                            'autofocus' => $attributeName == 'title',
                        ]) ?>
                    <?php } ?>
                </div>
                <div>
                    <?php foreach (['title_en', 'subtitle_en'] as $attributeName) { ?>
                        <?= $form->field($item, $attributeName)->textInput([
                            'autofocus' => $attributeName == 'title',
                        ]) ?>
                    <?php } ?>

                    <?= $form->field($item, 'description_en')->textarea([
                        'id' => 'description_en',
                    ]); ?>

                    <?php foreach (['seo_title_en', 'seo_description_en'] as $attributeName) { ?>
                        <?= $form->field($item, $attributeName)->textInput([
                            'autofocus' => $attributeName == 'title',
                        ]) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="uk-width-1-4">
            <?= $form->field($item, 'alias')->textInput() ?>
            <?= StatusSwitcher::widget([
                'status' => (bool)$item->published,
            ]); ?>
            <?= $form->field($item, 'created_date')->textInput([
                'id' => 'item_created_date',
            ]) ?>
            <?= $form->field($item, 'image_id')->widget(ImageSelector::class, [
                'selectType' => ImageSelector::SELECT_SINGLE,
                'buttonText' => 'Выбрать изображение',
            ]); ?>
        </div>
    </div>
</div>
<?= $form->field($item, 'id')->hiddenInput(['id' => 'item-id'])->label(false); ?>
<?php ActiveForm::end(); ?>

