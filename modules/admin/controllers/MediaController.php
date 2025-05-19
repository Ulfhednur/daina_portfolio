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

use app\models\Folder;
use app\models\Media;
use app\models\MediaGallery;
use app\widgets\ImageSelector\ImageSelector;
use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class MediaController extends FileController
{
    /**
     * {@inheritdoc}
     */
    protected string $modelClass = Media::class;

    /**
     * {@inheritdoc}
     */
    protected string $tmpl = 'mediaForm';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['only'][] = 'display';
        $behaviors['access']['only'][] = 'show';
        $behaviors['access']['rules'][] = [
            'actions' => ['show', 'display'],
            'allow' => true,
            'roles' => ['@'],
        ];
        $behaviors['verbs']['actions']['display'] = ['post'];
        $behaviors['verbs']['actions']['show'] = ['get'];
        return $behaviors;
    }

    public function actionCreate(int $id): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('file');
            $model = new Media();
            $model->load([
                'fileName' => $file->fullPath,
                'folder_id' => $id,
            ]);
            if ($model->validate()) {
                $model->imageContent = file_get_contents($file->tempName);
                $model->imageMime = $file->type;
                $model->save();
            } else {
                throw new HttpException(405, implode(', ', $model->getErrorSummary(true)));
            }
            unlink($file->tempName);
        }
        return $this->renderPanels($id);
    }

    public function actionDisplay(): Response|string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $selectType = match ($post['select_type']) {
            '-1' => ImageSelector::SELECT_NONE,
            '0' => ImageSelector::SELECT_SINGLE,
            '1' => ImageSelector::SELECT_MULTIPLE,
        };
        return $this->asJson([
            'html' => $this->renderPartial('@adminViews/Media/display', [
                'folders' => Folder::getTree(),
                'currentFolder' => Folder::getRoot(),
                'media' => Media::findAll(['folder_id' => 0]),
                'selectType' => $selectType,
                'allowMultiSelect' => $selectType != ImageSelector::SELECT_SINGLE,
                'selectedItems' => (int) $post['gallery_id'] ? MediaGallery::find()
                    ->select('media_id')
                    ->where(['item_id' => $post['gallery_id']])
                    ->column() : [],
            ]),
        ]);
    }

    public function actionShow(int $id): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $galleryId = (int) Yii::$app->request->get('gallery_id', 0);
        return $this->asJson([
            'files' => $this->renderPartial('@adminViews/layouts/filesPanel', [
                'folders' => Folder::findAll(['parent_id' => $id]),
                'currentFolder' => $id == 0 ? Folder::getRoot() : Folder::findOne(['id' => $id]),
                'media' => Media::findAll(['folder_id' => $id]),
                'allowMultiSelect' => (bool)Yii::$app->request->get('allow-multiselect', 0),
                'selectedItems' => $galleryId ? MediaGallery::find()
                                    ->select('media_id')
                                    ->where(['item_id' => $galleryId])
                                    ->column() : [],
            ]),
        ]);
    }
}