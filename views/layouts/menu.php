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
 */
use app\helpers\appHelper;
?>
<header class="main-header uk-text-center uk-flex uk-flex-column uk-flex-between uk-section">
    <h2 class="logo"><a href="/"><?= Yii::t('app', Yii::$app->name)?></a></h2>
    <nav class="uk-navbar-container uk-navbar-transparent">
        <div uk-navbar>
            <div class="uk-navbar-center">
                <ul class="uk-navbar-nav">
                    <li<?=appHelper::isMenuActive($this, 'gallery') ? ' class="uk-active"' : ''?>>
                        <a href="/gallery"><?= Yii::t('app', 'Галерея')?></a>
                    </li>
                    <li<?=appHelper::isMenuActive($this, 'contacts') ? ' class="uk-active"' : ''?>>
                        <a href="/contacts"><?= Yii::t('app', 'Контакты')?></a>
                    </li>
                    <li<?=appHelper::isMenuActive($this, 'about') ? ' class="uk-active"' : ''?>>
                        <a href="/about"><?= Yii::t('app', 'О себе')?></a>
                    </li>
                    <li<?=appHelper::isMenuActive($this, 'blog') ? ' class="uk-active"' : ''?>>
                        <a href="/blog"><?= Yii::t('app', 'Заметки')?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
