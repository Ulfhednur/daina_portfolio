<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var LoginForm $model */

use app\modules\admin\models\LoginForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
<div class="uk-container">

    <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle">
        <div>
            <h1>Аутентификация</h1>

            <p>Укажите имя пользователя и пароль:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => '/admin/login',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-12 col-form-label'],
                    'inputOptions' => ['class' => 'uk-input'],
                    'errorOptions' => ['class' => 'col-lg-12 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Вход', ['class' => 'uk-button uk-button-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
</div>
