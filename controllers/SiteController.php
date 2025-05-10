<?php

namespace app\controllers;

use app\models\ContactForm;
use Yii;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $path = explode('/', parse_url(Yii::$app->request->absoluteUrl, PHP_URL_PATH));
        if (!empty($path[1]) && $path[1] == env('ADMIN_URL')) {
            $error = [
                'class' => ErrorAction::class,
                'layout' => '@app/modules/admin/views/layouts/main.php',
                'view' => '@app/modules/admin/views/error.php'
            ];
        } else {
            $error = [
                'class' => ErrorAction::class
            ];
        }
        return [
            'error' => $error,
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
