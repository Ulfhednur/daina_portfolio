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

use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\Response;

abstract class ItemController extends Controller
{
    /**
     * Default model class
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * Default template folder
     *
     * @var string
     */
    protected string $tmpl;

    /**
     * Default model instance
     *
     * @var ActiveRecord
     */
    protected ActiveRecord $model;

    protected bool $forceRedirect = false;

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
                        'actions' => ['edit', 'display', 'delete', 'save'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'edit' => ['post', 'get'],
                    'display' => ['post', 'get'],
                    'delete' => ['post'],
                    'save' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Getting model instance
     *
     * @param int|null $id
     * @param bool     $internalErrorHandling
     *
     * @return null|ActiveRecord
     * @throws HttpException
     */
    protected function getItem(?int $id, bool $internalErrorHandling = true): ?ActiveRecord
    {
        if ($id) {
            $item = call_user_func_array([$this->modelClass, 'findOne'], [['id' => $id]]);
            if (empty($item->id) && $internalErrorHandling) {
                throw new HttpException(404, 'Запрашиваемая страница не найдена');
            }
        } else {
            $class = $this->modelClass;
            $item = new $class();
        }
        return $item;
    }

    /**
     * Entity edit form
     *
     * @param int|null $id
     *
     * @return Response|string
     * @throws Exception
     * @throws HttpException
     */
    public function actionEdit(?int $id = null): Response|string
    {
        $post = Yii::$app->request->post();
        if (!$id && !empty($post['id'])) {
            $id = $post['id'];
        }

        $item = $this->getItem($id);

        if (array_key_exists('id', $post)) {
            if ($item->load($post) && $item->validate()) {
                $item->save();
                if (!$this->forceRedirect && empty($item->getErrorSummary(false))) {
                    Yii::$app->session->setFlash('success', 'Сохранено');
                    return $this->redirect('/' . env('ADMIN_URL') . '/' . strtolower($this->tmpl) . '/edit/' . $item->id);
                }
            } else {
                Yii::$app->session->setFlash('error', $item->getErrorSummary(true));
            }
        }

        if ($this->forceRedirect && empty($item->getErrorSummary(false))) {
            Yii::$app->session->setFlash('success', 'Сохранено');
            return $this->redirect($this->getRedirectUrl());
        }
        return $this->render('@adminViews/' . $this->tmpl . '/edit', [
            'item' => $item,
        ]);
    }

    /**
     * Save entity and redirect to list;
     *
     * @param int|null $id
     *
     * @return Response|string
     * @throws Exception
     * @throws HttpException
     */
    public function actionSave(?int $id = null): Response|string
    {
        $this->forceRedirect = true;

        return $this->actionEdit($id);
    }

    /**
     * Prepares search params
     *
     * @return mixed
     */
    protected static function getSearchParams(): mixed
    {
        $search = Yii::$app->request->post('search');
        return $search ?? null;
    }

    /**
     * Entity items list
     *
     * @return Response|string
     */
    public function actionDisplay(): Response|string
    {
        $class = $this->modelClass;
        $item = new $class();

        $session = Yii::$app->session;
        if (Yii::$app->request->isPost) {
            $pageSize = Yii::$app->request->post('pageSize', $session->get('pageSize', env('PAGE_SIZE')));
        } else {
            $pageSize = $session->get('pageSize', env('PAGE_SIZE'));
        }
        $session->set('pageSize', $pageSize);

        $dataProvider = new ActiveDataProvider([
            'query' => call_user_func($this->modelClass . '::Search', static::getSearchParams()),
            'pagination' => ['pageSize' => $pageSize],
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('@adminViews/' . $this->tmpl . '/display', [
            'model' => $item,
            'items' => $dataProvider->getModels(),
            'pagination' => $dataProvider->getPagination(),
            'pageSize' => $pageSize
        ]);
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
        $success = [];
        $errors = [];
        $errors_404 = [];

        foreach (Yii::$app->request->post('ids') as $id) {
            $id = (int)$id;
            if ($item = $this->getItem($id, false)) {
                if ($item->delete()) {
                    $success[] = $id;
                } else {
                    $errors[$id] = implode('<br>', $item->getErrorSummary(true));
                }
            } else {
                $errors_404[] = $id;
            }
        }

        if (!empty($errors_404)) {
            Yii::$app->session->setFlash('error', "Запись(и) id " . implode(', ', $errors) . " не удалена(ы)");
        }

        if (!empty($errors)) {
            $msg = '';
            foreach ($errors as $iid => $error) {
                $msg = "Не удалось удалить запись id {$iid}: {$error}";
            }
            Yii::$app->session->setFlash('error', $msg);
        }

        if (!empty($success)) {
            Yii::$app->session->setFlash('success', "Запись(и) id " . implode(', ', $success) . " удалена(ы)");
        }

        return $this->redirect($this->getRedirectUrl());
    }

    /**
     * Url to redirect after post functions
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return '/' . env('ADMIN_URL') . '/' . strtolower($this->tmpl);
    }

    /**
     * @return array|Response
     */
    public function actionAutocomplete(): array|Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $q = Yii::$app->request->post('q');
        $query = call_user_func($this->modelClass . '::find');
        return $this->asJson($query->select(['title', 'id'])
            ->where(['like', 'title', $q . '%', false])
            ->asArray()
            ->all());
    }
}