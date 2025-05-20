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
use app\helpers\langHelper;
use yii\helpers\Html;

FrontendAsset::register($this);
$langData = langHelper::getLangHrefs(['/']);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);

foreach($langData as $lang){
    $this->registerLinkTag(['rel' => 'alternate', 'hreflang' => $lang['hreflang'], 'href' => $lang['href']]);
}
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="uk-height-1-1">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body class="uk-height-1-1 uk-flex uk-flex-column uk-flex-center homepage">
<div class="homepage-bg uk-animation-kenburns uk-animation-reverse uk-height-1-1 uk-width-1-1"></div>
<div class="content">
    <?php $this->beginBody() ?>
    <?= $this->render('@app/views/layouts/menu.php', ['langData' => $langData]) ?>
    <?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage() ?>
