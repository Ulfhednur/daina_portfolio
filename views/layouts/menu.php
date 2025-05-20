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

$lang = langHelper::getCurrentLang();

?>
<header class="main-header uk-text-center uk-section" id="#page-top">
    <div class="uk-container uk-container-xlarge uk-flex uk-flex-column uk-flex-between ">
        <h2 class="logo"><a href="<?= Url::to(['/', 'language' => $lang]) ?>"><?= Yii::t('app', Yii::$app->name) ?></a></h2>
        <nav class="uk-navbar-container uk-navbar-transparent">
            <div uk-navbar>
                <div class="uk-navbar-center">
                    <ul class="uk-navbar-nav">
                        <li<?= appHelper::isMenuActive($this, 'gallery') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['gallery/display', 'language' => $lang]) ?>"><?= Yii::t('app', 'Галерея') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'contacts') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['site/contacts', 'language' => $lang]) ?>"><?= Yii::t('app', 'Контакты') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'about') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['site/about', 'language' => $lang]) ?>"><?= Yii::t('app', 'О себе') ?></a>
                        </li>
                        <li<?= appHelper::isMenuActive($this, 'blog') ? ' class="uk-active"' : '' ?>>
                            <a href="<?= Url::to(['blog/display', 'language' => $lang]) ?>"><?= Yii::t('app', 'Заметки') ?></a>
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
    <a href="#page-top" class="uk-icon-button" id="scroll-to-top" uk-icon="arrow-up" uk-scroll></a>
</header>
