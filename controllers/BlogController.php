<?php

namespace app\controllers;

use app\models\Post;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\HttpException;

class BlogController extends PortfolioController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['display', 'show'],
                'rules' => [
                    [
                        'actions' => ['display', 'show'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'display' => ['get'],
                    'show' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionDisplay(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find()->andWhere(['published' => 1]),
            'pagination' => ['pageSize' => env('DEFAULT_PAGE_SIZE')],
            'sort' => [
                'defaultOrder' => [
                    'created_date' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('@app/views/blog/display', [
            'items' => $dataProvider->getModels(),
            'pagination' => $dataProvider->getPagination(),
        ]);
    }

    /**
     * Displays about page.
     *
     * @param string $alias
     *
     * @return string
     */
    public function actionShow(string $alias): string
    {
        $item = Post::findOne([
            'alias' => $alias,
            'published' => 1,
        ]);
        if (empty($item)) {
            return new HttpException(404, Yii::t('app', 'Галерея не найдена'));
        }
        Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $item->seo_description,
                'id'=>"blog_show"
            ],"blog_show");
        return $this->render('@app/views/blog/show', [
            'item' => $item,
        ]);
    }
}
