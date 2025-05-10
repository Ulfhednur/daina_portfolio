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

use app\models\Item;
use app\models\Page;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\Response;

class OrderableController extends ItemController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['only'][] = 'order';
        $behaviors['access']['only'][] = 'publish';
        $behaviors['access']['only'][] = 'unpublish';
        $behaviors['access']['rules'][] = [
            'actions' => ['order', 'publish', 'unpublish'],
            'allow' => true,
            'roles' => ['@'],
        ];
        $behaviors['verbs']['actions']['order'] = ['post'];
        $behaviors['verbs']['actions']['publish'] = ['post'];
        $behaviors['verbs']['actions']['unpublish'] = ['post'];
        return $behaviors;
    }

    public function actionOrder(): Response|string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = Yii::$app->request->post('items');
        call_user_func_array([$this->modelClass, 'updateOrdering'], [$items]);

        return $this->asJson(['success' => true]);
    }

    protected function PublicationStatusChange(int $status, string $successMsg): Response|string
    {
        $post = Yii::$app->request->post();
        $success = [];
        $errors = [];

        foreach ($post['ids'] as $id) {
            /** @var Item $item */
            if ($item = $this->getItem((int)$id, false)) {
                $item->published = $status;
                $item->updateAttributes(['published']);
                $success[] = $item->title;
            } else {
                $errors[] = $item->id;
            }
        }

        if (!empty($errors)) {
            Yii::$app->session->setFlash('error', "Запись(и) id ".implode(', ', $errors)." не найдена(ы)");
        }

        if (!empty($success)) {
            Yii::$app->session->setFlash('success', "Запись(и) id ".implode(', ', $success)." {$successMsg}");
        }

        return $this->redirect('/' . env('ADMIN_URL') . '/' . strtolower($this->tmpl));
    }

    public function actionPublish(): Response|string
    {
        return $this->PublicationStatusChange(1, 'опубликована(ы)');
    }

    public function actionUnpublish(): Response|string
    {
        return $this->PublicationStatusChange(0, 'снята(ы) с публикации');
    }
}