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
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\Response;

abstract class FileController extends ItemController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['edit', 'display', 'delete', 'save'],
                'rules' => [
                    [
                        'actions' => ['edit', 'create', 'delete', 'save'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'edit' => ['post', 'get'],
                    'create' => ['post', 'get'],
                    'delete' => ['post'],
                    'save' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Создание объекта
     *
     * @param int $id
     *
     * @return Response
     */
    public abstract function actionCreate(int $id): Response;

    /**
     * Форма редактирования
     *
     * @param int|null $id
     *
     * @return Response
     * @throws HttpException
     */
    public function actionEdit(?int $id = null): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->asJson([
            'form' => $this->renderPartial('@adminViews/layouts/' . $this->tmpl . '.php', [
                'item' => $this->getItem($id),
            ]),
        ]);
    }

    /**
     * Save entity and redirect to list;
     *
     * @param int|null $id
     *
     * @return Response
     * @throws HttpException|Exception
     */
    public function actionSave(?int $id = null): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = Yii::$app->request->post();

        if ($item = $this->getItem($id)) {
            /** @var Media|Folder $item */
            if ($item->load($data)) {
                if ($item->save($data)) {
                    return $this->renderPanels($item->parent_id, $item->id);
                }
                return $this->asJson(new \HttpException(implode('<br>', $item->getErrorSummary(true)), 417));
            }
            return $this->asJson(new \HttpException(implode('<br>', $item->getErrorSummary(true)), 417));
        }

        return $this->asJson(new \HttpException('Объект не найден', 404));
    }

    /**
     * Entity remove
     *
     * @return Response
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete(): Response
    {
        $data = Yii::$app->request->post();

        $success = [];
        $errors = [];
        $errors_404 = [];

        $origModelClass = $this->modelClass;
        foreach (['Folder', 'Media'] as $modelClass) {
            if (!empty($data[$modelClass])) {
                $this->modelClass = '\\app\\models\\' . $modelClass;
                foreach ($data[$modelClass] as $id) {
                    /** @var Folder|Media $item */
                    if ($item = $this->getItem((int)$id, false)) {
                        $title = $item->title;
                        if ($item->delete()) {
                            $success[] = $title;
                        } else {
                            $errors[$title] = implode('<br>', $item->getErrorSummary(true));
                        }
                    } else {
                        $errors_404[] = $id;
                    }
                }
            }
        }
        $this->modelClass = $origModelClass;

        if (!empty($errors_404)) {
            Yii::$app->session->setFlash('error', "Объект(ы) " . implode(', ', $errors_404) . " не найден(ы)");
        }

        if (!empty($errors)) {
            $msg = 'Не удалось удалить каталог(и)<br>';
            foreach ($errors as $key => $value) {
                $msg .= $key . ': ' . $value . '<br>';
            }
            Yii::$app->session->setFlash('error', $msg);
        }

        if (!empty($success)) {
            Yii::$app->session->setFlash('success', "Объект(ы) id " . implode(', ', $success) . " успешно удален(ы)");
        }

        return $this->renderPanels((int)$data['parent_id']);
    }

    /**
     * @param int      $parentId
     * @param int|null $itemId
     *
     * @return Response
     * @throws HttpException
     */
    protected function renderPanels(int $parentId, ?int $itemId = null): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($parentId == 0) {
            $currentFolder = Folder::getRoot();
        } else {
            $currentFolder = Folder::findOne(['id' => $parentId]);
        }
        if (empty($currentFolder)) {
            throw new HttpException('Директория не найдена', 404);
        }
        $galleryId = Yii::$app->request->post('gallery_id');
        return $this->asJson([
            'folders' => $this->renderPartial('@adminViews/layouts/folderPanel.php', [
                'currentFolder' => $currentFolder,
                'level' => 0,
                'folders' => Folder::getTree(),
            ]),
            'files' => $this->renderPartial('@adminViews/layouts/filesPanel.php', [
                'currentFolder' => $currentFolder,
                'media' => Media::findAll(['folder_id' => $parentId]),
                'folders' => Folder::findAll(['parent_id' => $parentId]),
                'allowMultiSelect' => Yii::$app->request->isPost ? (bool)Yii::$app->request->post('allow-multiselect', 0) : (bool)Yii::$app->request->get('allow-multiselect', 0),
                'selectedItems' => $galleryId
                    ? MediaGallery::find()
                        ->select('media_id')
                        ->where(['item_id' => $galleryId])
                        ->column()
                    : [],
            ]),
            'form' => $itemId ? $this->renderPartial('@adminViews/layouts/' . $this->tmpl . '.php', [
                'item' => $this->getItem($itemId),
            ]) : null,
        ]);
    }
}