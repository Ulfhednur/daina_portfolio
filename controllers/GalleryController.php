<?php

namespace app\controllers;

use app\models\Gallery;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class GalleryController extends PortfolioController
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
     * @param string $alias
     *
     * @return string
     */
    public function actionDisplay(): string
    {
        return $this->render('@app/views/gallery/display', [
            'items' => Gallery::find()
                ->andWhere(['published' => 1])
                ->orderBy(['ordering' => SORT_ASC])
                ->all()
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
        $item = Gallery::findOne([
            'alias' => $alias,
            'published' => 1,
        ]);
        if (empty($item)) {
            throw new NotFoundHttpException(Yii::t('error', 'Галерея не найдена'));
        }
        return $this->render('@app/views/gallery/show', [
            'item' => $item,
        ]);
    }
}
