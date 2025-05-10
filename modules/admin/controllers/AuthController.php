<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\base\InvalidRouteException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['get'],
                    'login' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     * @throws InvalidRouteException
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->response->redirect('/' . env('ADMIN_URL') . '/gallery');
        }

        $model = new \app\modules\admin\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->response->redirect('/' . env('ADMIN_URL') . '/gallery');
        }

        $model->password = '';
        return $this->render('@app/modules/admin/views/login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     * @throws InvalidRouteException
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->response->redirect('/'.env('ADMIN_URL').'/login');
    }
}
