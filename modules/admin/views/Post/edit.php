<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\assets\admin\AdminAsset;
use app\assets\DateTimePicker\DateTimePickerAsset;
use app\models\Page;
use app\widgets\AdminToolbar\EditToolbar;
use app\widgets\ImageSelector\ImageSelector;
use app\widgets\Status\StatusSwitcher;
use conquer\codemirror\CodemirrorAsset;
use app\widgets\CodemirrorWidget;
use yii\widgets\ActiveForm;

/** @var Page $item */

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
        'options' => ['item_id' =>  $item->id]
    ]) ?>
    <div class="uk-container uk-container-expand">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-3-4">
                <?php foreach (['title', 'alias', 'subtitle', 'seo_title', 'seo_description'] as $attributeName) {?>
                    <?= $form->field($item, $attributeName)->textInput([
                        'autofocus' => $attributeName == 'title',
                    ]) ?>
                <?php } ?>

                <?= $form->field($item, 'description')->widget(CodemirrorWidget::class, [
                    'assets'=>[
                        CodemirrorAsset::ADDON_FOLD_XML_FOLD,
                        CodemirrorAsset::ADDON_FOLD_FOLDCODE,
                        CodemirrorAsset::ADDON_FOLD_BRACE_FOLD,
                        CodemirrorAsset::ADDON_FOLD_INDENT_FOLD,
                        CodemirrorAsset::ADDON_SELECTION_ACTIVE_LINE,
                        CodemirrorAsset::ADDON_EDIT_MATCHBRACKETS,
                        CodemirrorAsset::ADDON_EDIT_MATCHTAGS,
                        CodemirrorAsset::ADDON_EDIT_TRAILINGSPACE,
                        CodemirrorAsset::ADDON_EDIT_CLOSETAG,
                        CodemirrorAsset::ADDON_EDIT_CLOSEBRACKETS,
                        CodemirrorAsset::MODE_XML,
                    ],
                    'options' => [
                        'rows' => 20,
                    ],
                    'settings' => [
                        'showTrailingSpace' => true,
                        'mode' => 'xml',
                        'lineNumbers' => true,
                        'matchBrackets' => true,
                        'autoCloseBrackets' => true,
                        'autoCloseTags' => true,
                        'matchTags' => true,
                    ],
                ]); ?>
            </div>
            <div class="uk-width-1-4">
                <?=StatusSwitcher::widget([
                    'status' => (bool) $item->published
                ]); ?>
                <?=$form->field($item, 'created_date')->textInput([
                    'id' => 'item_created_date',
                ]) ?>
                <?=$form->field($item, 'image_id')->widget(ImageSelector::class, [
                    'selectType' => ImageSelector::SELECT_SINGLE,
                    'buttonText' => 'Выбрать изображение'
                ]); ?>
            </div>
        </div>
    </div>
    <?=$form->field($item, 'id')->hiddenInput(['id' => 'item-id'])->label(false); ?>
    <?php ActiveForm::end(); ?>

