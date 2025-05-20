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
 * @var array        $langData
 */

use app\helpers\appHelper;
use app\helpers\langHelper;
use yii\helpers\Url;

$currentLang = langHelper::getCurrentLang();

?>
<div id="offcanvas-menu" uk-offcanvas="overlay: true; mode: push">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <ul class="uk-nav uk-nav-default uk-nav-medium uk-margin-top">
            <li<?= appHelper::isMenuActive($this, 'gallery') ? ' class="uk-active"' : '' ?>>
                <a href="<?= Url::to(['gallery/display', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Галереи') ?></a>
            </li>
            <li<?= appHelper::isMenuActive($this, 'contacts') ? ' class="uk-active"' : '' ?>>
                <a href="<?= Url::to(['site/contacts', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Контакты') ?></a>
            </li>
            <li<?= appHelper::isMenuActive($this, 'about') ? ' class="uk-active"' : '' ?>>
                <a href="<?= Url::to(['site/about', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'О себе') ?></a>
            </li>
            <li<?= appHelper::isMenuActive($this, 'blog') ? ' class="uk-active"' : '' ?>>
                <a href="<?= Url::to(['blog/display', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Заметки') ?></a>
            </li>
        </ul>
    </div>
</div>
<header class="main-header uk-text-center uk-section" id="page-top">
    <nav class="uk-navbar-container uk-navbar-transparent mobile-menu-block uk-hidden@s">
        <div uk-navbar>
            <div class="uk-navbar-left">
                <div class="uk-navbar-item">
                    <div class="uk-grid-small uk-child-width-1-2 uk-grid-divider" uk-grid>
                        <div><a class="uk-active uk-disabled uk-text-muted uk-margin-left"
                                href="#"><?= langHelper::$langTitles[langHelper::getCurrentLang()] ?></a></div>
                        <?php foreach ($langData as $lang) { ?>
                            <div><a href="<?= $lang['href'] ?>"><?= $lang['langTitle'] ?></a></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="uk-navbar-right">
                <div class="uk-navbar-item">
                    <button id="mobile-menu-button" class="uk-button" uk-toggle="target: #offcanvas-menu"><span
                                uk-icon="more-vertical"></span></button>
                </div>
            </div>
        </div>
    </nav>
    <div class="uk-container uk-container-xlarge uk-flex uk-flex-column uk-flex-between ">
        <h2 class="logo"><a
                    href="<?= Url::to(['/', 'language' => $currentLang]) ?>"><?= Yii::t('app', Yii::$app->name) ?></a>
        </h2>
        <nav class="uk-navbar-container uk-navbar-transparent menu-block">
            <div uk-navbar>
                <div class="uk-navbar-center">
                    <ul class="uk-navbar-nav">
                        <li<?= appHelper::isMenuActive($this, 'gallery') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['gallery/display', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Галереи') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'contacts') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['site/contacts', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Контакты') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'about') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['site/about', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'О себе') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'blog') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['blog/display', 'language' => $currentLang]) ?>"><?= Yii::t('app', 'Заметки') ?></a>
                        </li>
                    </ul>
                </div>

                <div class="uk-navbar-right lang-switcher">
                    <div class="uk-navbar-item">
                        <div class="uk-grid-small uk-child-width-1-2 uk-grid-divider" uk-grid>
                            <div><a class="uk-active uk-disabled uk-text-muted"
                                    href="#"><?= langHelper::$langTitles[langHelper::getCurrentLang()] ?></a></div>
                            <?php foreach ($langData as $lang) { ?>
                                <div><a href="<?= $lang['href'] ?>"><?= $lang['langTitle'] ?></a></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
