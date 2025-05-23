<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="uk-container uk-container-large uk-height-1-1 uk-text-center">
    <div class="uk-section uk-overlay uk-overlay-primary uk-height-1-1">
        <h1><?= Html::encode(Yii::t('error', $this->title)) ?></h1>

        <div class="uk-section uk-text-large">
            <?= nl2br(Html::encode(Yii::t('error', $message))) ?>
        </div>

        <p>
            <?= Yii::t('error', 'Указанная выше ошибка произошла во время обработки вашего запроса веб-сервером.') ?>
        </p>
        <p>
            <?= Yii::t('error', 'Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.') ?>
        </p>
    </div>
</div>
