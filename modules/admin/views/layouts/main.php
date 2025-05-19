<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\Uikit\UikitAsset;
use app\widgets\Alert;
use app\helpers\appHelper;
use app\widgets\ImageSelector\ImageSelector;
use yii\bootstrap5\Html;

UikitAsset::register($this);
//AdminAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=yes']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$adminUrl = env('ADMIN_URL');
$mainMenu = [
    [
        'label' => 'Галереи',
        'url' => '/' . $adminUrl . '/gallery',
        'active' => appHelper::isMenuActive($this, 'gallery')
    ],
    [
        'label' => 'Страницы',
        'url' => '/' . $adminUrl . '/page',
        'active' => appHelper::isMenuActive($this, 'page')
    ],
    [
        'label' => 'Посты',
        'url' => '/' . $adminUrl . '/post',
        'active' => appHelper::isMenuActive($this, 'post')
    ],
];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="uk-height-1-1">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript">
        const adminUrl = '/<?=$adminUrl?>';
    </script>
    <?php $this->head() ?>
</head>
<body class="uk-height-1-1 uk-flex uk-flex-column uk-flex-between">
<?php $this->beginBody() ?>

<header id="header" class="uk-section-secondary">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">
            <a href="<?=Yii::$app->homeUrl?>" class="uk-navbar-item uk-logo"><img src="<?=Yii::$app->params['siteLogo']?>" alt="<?=Yii::$app->name?>" /></a>
            <?php if (!Yii::$app->user->isGuest) { ?>
                <ul class="uk-navbar-nav">
                    <?php foreach ($mainMenu as $menuItem) {?>
                        <li<?=$menuItem['active'] ? ' class="uk-active"' : ''?>><a href="<?=$menuItem['url']?>"><?=$menuItem['label']?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <?php if (Yii::$app->user->isGuest) { ?>
                    <li><a href="/<?=$adminUrl?>/auth/login"><span uk-icon="sign-in"></span></a></li>
                <?php } else { ?>
                    <li><a href="/<?=$adminUrl?>/auth/logout"><span uk-icon="sign-out"></span></a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>

<main id="main" class="uk-height-1-1">
        <?php Alert::widget(['view' => &$this]) ?>
        <?= $content ?>
</main>

<footer id="footer" class="uk-section-secondary uk-section-muted">
    <div class="uk-container uk-container-expand">
        <div class="uk-flex uk-flex-between uk-text-muted">
            <div class="uk-text-left">&copy; Tempadmin <?= date('Y') ?></div>
            <div class="uk-text-right"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
