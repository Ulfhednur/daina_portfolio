<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Gallery;
use app\models\Page;
use app\models\Post;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\HttpException;
use yii\web\Response;

class SiteController extends PortfolioController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'contacts', 'about'],
                'rules' => [
                    [
                        'actions' => ['index', 'contacts', 'about'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'about' => ['get'],
                    'contacts' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        $error = [
            'class' => ErrorAction::class,
        ];

        $path = explode('/', parse_url(Yii::$app->request->absoluteUrl, PHP_URL_PATH));
        if (!empty($path[1]) && $path[1] == env('ADMIN_URL')) {
            $error['layout'] = '@app/modules/admin/views/layouts/main.php';
            $error['view'] = '@app/modules/admin/views/error.php';
        } else {
            $error['layout'] = '@app/views/layouts/error.php';
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
    public function actionIndex(): string
    {
        $this->layout = '@app/views/layouts/home.php';
        return $this->render('index');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContacts(): Response|string
    {
        if (Yii::$app->request->isPost) {
            $model = new ContactForm();
            if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('contactFormSubmitted');

                return $this->refresh();
            }
        }
        return $this->render('contact', [
            'model' => new ContactForm(),
            'item' => Page::findOne(['alias' => 'contacts']),
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about', [
            'item' => Page::findOne(['alias' => 'about']),
        ]);
    }
}
