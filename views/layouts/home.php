<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

/**
 * @var yii\web\View $this
 * @var string       $content
 */

use app\assets\Frontend\FrontendAsset;
use yii\helpers\Html;

FrontendAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="uk-height-1-1">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="uk-height-1-1 uk-flex uk-flex-column uk-flex-center homepage">
<div class="homepage-bg uk-animation-kenburns uk-animation-reverse uk-height-1-1 uk-width-1-1"></div>
<div class="content">
    <?php $this->beginBody() ?>
    <?= $this->render('@app/views/layouts/menu.php') ?>
    <?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage() ?>
