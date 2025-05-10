<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\modules\admin\controllers;

use app\models\Gallery;
use app\models\MediaGallery;
use Yii;
use yii\web\Response;

class GalleryController extends OrderableController
{
    /**
     * {@inheritdoc}
     */
    protected string $modelClass = Gallery::class;

    /**
     * {@inheritdoc}
     */
    protected string $tmpl = 'Gallery';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['only'][] = 'add-item';
        $behaviors['access']['only'][] = 'remove-item';
        $behaviors['access']['only'][] = 'show';
        $behaviors['access']['rules'][] = [
            'actions' => ['show', 'add-item', 'remove-item'],
            'allow' => true,
            'roles' => ['@'],
        ];
        $behaviors['verbs']['actions']['remove-item'] = ['post'];
        $behaviors['verbs']['actions']['add-item'] = ['post'];
        $behaviors['verbs']['actions']['show'] = ['get'];
        return $behaviors;
    }

    public function actionShow(int $id): string|Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->asJson([
            'html' => $this->renderPartial('@adminViews/Gallery/layouts/photos.php', [
                'items' => Gallery::findOne(['id' => $id])->media,
            ]),
        ]);
    }

    public function actionAddItem(int $id): string|Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaId = Yii::$app->request->post('media_id');
        if (!MediaGallery::find()->where(['media_id' => $mediaId, 'item_id' => $id])->exists()) {
            $mediaGallery = new MediaGallery();
            $mediaGallery->item_id = $id;
            $mediaGallery->media_id = $mediaId;
            $mediaGallery->save();
        }

        return $this->asJson(['success' => true]);
    }

    public function actionRemoveItem(int $id): string|Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mediaId = Yii::$app->request->post('media_id');
        if ($mediaGallery = MediaGallery::findOne(['media_id' => $mediaId, 'item_id' => $id])) {
            $mediaGallery->delete();
        }

        return $this->asJson(['success' => true]);
    }
}