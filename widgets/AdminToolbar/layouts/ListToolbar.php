<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

/** @var array $options */

use app\widgets\ImageSelector\ImageSelector;

?>

<header id="admin-tool-panel" class="uk-section-muted">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li><button class="uk-button uk-button-primary" id="create-button" data-list-action="edit"><span uk-icon="plus"></span> Создать</button></li>
                <li><button class="uk-button" id="publish-button" data-list-action="publish"><span uk-icon="check"></span> Опубликовать</button></li>
                <li><button class="uk-button uk-text-danger" id="unpublish-button" data-list-action="unpublish"><span uk-icon="ban"></span> Разпубликовать</button></li>
                <?php if (!empty($options['order'])) {?>
                    <li><button class="uk-button" id="order-button" data-list-action="reorder"><span uk-icon="arrow-down-arrow-up"></span> Сохранить сортировку</button></li>
                <?php } ?>
                <li>
                    <?=ImageSelector::widget([
                        'selectType' => ImageSelector::SELECT_NONE,
                        'name' => 'img',
                    ]) ?>
                </li>
            </ul>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><button class="uk-button uk-button-danger" id="remove-button" data-list-action="delete"><span uk-icon="trash"></span></button></li>
            </ul>
        </div>
    </nav>
</header>
