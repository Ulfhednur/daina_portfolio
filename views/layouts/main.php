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

FrontendAsset::register($this);
$langData = langHelper::getLangHrefs($this->params['lang_path']);
$request = Yii::$app->getRequest();

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
    <title><?= Yii::t('app', Yii::$app->name)?><?=$this->title ? ' - ' . $this->title : ''?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body class="uk-flex uk-flex-column uk-flex-between uk-height-1-1">
<?= $this->render('@app/views/layouts/menu.php', ['langData' => $langData]) ?>
<?php $this->beginBody() ?>

<main id="main" class="uk-flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
        <?php endif ?>
        <?= $content ?>
    </div>
</main>

<div class="fake-footer"></div>

<?php $this->endBody() ?>

<a href="#page-top" class="uk-icon-button" id="scroll-to-top" uk-icon="arrow-up" uk-scroll></a>
</body>
</html>
<?php $this->endPage() ?>
