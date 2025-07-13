<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\models\Page;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

/**
 * @var yii\web\View              $this
 * @var yii\bootstrap5\ActiveForm $form
 * @var app\models\ContactForm    $model
 * @var Page                      $item
 */

$this->title = $item->title;
//$form->field($model, 'verifyCode')->widget(Captcha::class, [
//                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
//                        ])
$this->params['lang_path'] = ['site/contacts'];
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container uk-container-small">
        <h1 class="uk-h1 uk-text-center"><?= Html::encode($this->title) ?></h1>
            <div uk-grid class="uk-child-width-1-2@m">
                <div><?= $item->description ?></div>

                <div class="row">
                </div>
            </div>
    </div>
</section>
