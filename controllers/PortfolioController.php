<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\controllers;

use app\helpers\langHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class PortfolioController extends Controller
{
    /**
     * @inheritDoc
     *
     * @throws \yii\web\BadRequestHttpException|NotFoundHttpException
     */
    public function beforeAction($action)
    {
        langHelper::setCurrentLang();
        return parent::beforeAction($action);
    }


}