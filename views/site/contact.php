<?php

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

$this->title = 'Обратная связь';
//$form->field($model, 'verifyCode')->widget(Captcha::class, [
//                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
//                        ])
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container uk-container-small">
        <h1 class="uk-h1 uk-text-center"><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="alert alert-success">
                Thank you for contacting us. We will respond to you as soon as possible.
            </div>

            <p>
                Note that if you turn on the Yii debugger, you should be able
                to view the mail message on the mail panel of the debugger.
                <?php if (Yii::$app->mailer->useFileTransport): ?>
                    Because the application is in development mode, the email is not sent but saved as
                    a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                                                                                                        Please configure the
                    <code>useFileTransport</code> property of the <code>mail</code>
                    application component to be false to enable email sending.
                <?php endif; ?>
            </p>

        <?php else: ?>
            <div uk-grid class="uk-child-width-1-2@m">
                <div><?= $item->description ?></div>

                <div class="row">
                    <div class="col-lg-5">

                        <?php $form = ActiveForm::begin([
                            'id' => 'contact-form',
                            'fieldConfig' => [
                                'template' => "{label}\n{input}\n{error}",
                                'inputOptions' => ['class' => 'uk-input'],
                                'errorOptions' => ['class' => 'uk-text-danger'],
                            ],
                        ]); ?>

                        <?php foreach (['name', 'email', 'subject'] as $field) { ?>

                            <?= $form->field($model, $field)->textInput([
                                'autofocus' => $field === 'name',
                                'placeholder' => $model->getAttributeLabel($field),
                                'class' => 'uk-input uk-margin-bottom',
                            ])->label(false) ?>

                        <?php } ?>

                        <?= $form->field($model, 'body')->textarea([
                            'rows' => 6,
                            'placeholder' => $model->getAttributeLabel('body'),
                            'class' => 'uk-textarea uk-margin-bottom',
                        ])->label(false) ?>

                        <div class="uk-text-right">
                            <?= Html::submitButton(\Yii::t('app', 'Отправить'), ['class' => 'uk-button uk-button-secondary', 'name' => 'contact-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
