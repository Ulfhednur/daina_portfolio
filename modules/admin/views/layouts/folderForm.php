<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

/** @var Folder $item */

use app\models\Folder;
use yii\widgets\ActiveForm;

$formAction = '/' . env('ADMIN_URL') . '/folder/save';
if (!empty($item->id)) {
    $formAction .= '/' . $item->id;
}
?>
<?php $form = ActiveForm::begin([
    'id' => 'media-manager-form',
    'action' => $formAction,
    'options' => [
        'class' => 'uk-margin-top media-manager-form'
    ],
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'inputOptions' => ['class' => 'uk-input'],
        'errorOptions' => ['class' => 'uk-text-danger'],
    ],
]); ?>
<?= $form->field($item, 'title')->textInput([
    'autofocus' => true,
]) ?>
<div class="uk-margin-top">
    <button class="uk-button uk-button-primary uk-button-small" type="submit">Сохранить</button>
    <button class="uk-button uk-button-small" type="reset">Отмена</button>
</div>
<?php ActiveForm::end(); ?>

