<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="uk-container uk-text-center">
    <div class="uk-section">
        <h1><?= Html::encode(Yii::t('app', $this->title)) ?></h1>

        <div class="uk-background-danger uk-section">
            <?= nl2br(Html::encode(Yii::t('app', $message))) ?>
        </div>

        <p>
            <?= Yii::t('app', 'Указанная выше ошибка произошла во время обработки вашего запроса веб-сервером.') ?>
        </p>
        <p>
            <?= Yii::t('app', 'Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.') ?>
        </p>
    </div>
</div>
