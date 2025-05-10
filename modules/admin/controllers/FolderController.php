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
use Yii;
use yii\db\Exception;
use yii\web\Response;

class FolderController extends FileController
{
    /**
     * Default model class
     * @var string
     */
    protected string $modelClass = Folder::class;

    /**
     * Default template folder
     * @var string
     */
    protected string $tmpl = 'folderForm';

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function actionCreate(int $id): Response
    {
        $model = new Folder();
        $i = 0;
        do {
            $model->load([
               'parent_id' => $id,
               'title' => 'new-folder' . ($i == 0 ? '' : '-'.$i)
            ]);
            $i++;
        } while (!$model->validate());

        $model->save(false);

        Yii::$app->session->setFlash('success', "Каталог {$model->title} создан.");

        return $this->renderPanels($id, $model->id);
    }
}