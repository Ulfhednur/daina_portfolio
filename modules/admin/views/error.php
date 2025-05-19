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
/** @var string $name */
/** @var string $message */

/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="uk-container uk-height-1-1">

    <div class="uk-flex uk-flex-column uk-flex-center uk-text-center uk-height-1-1">
        <h1><?= Html::encode(Yii::t('app', $this->title)) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
</div>
