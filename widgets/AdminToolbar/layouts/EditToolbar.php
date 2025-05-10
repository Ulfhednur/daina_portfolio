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
 * @var array $options
 */

use app\widgets\ImageSelector\ImageSelector;

?>

<header id="admin-tool-panel" class="uk-section-muted">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-left">
            <ul class="uk-navbar-nav">
                <li>
                    <button class="uk-button uk-button-primary" id="apply-button" data-edit-action="edit"
                            data-add-id="true"><span uk-icon="push"></span> Сохранить
                    </button>
                </li>
                <li>
                    <button class="uk-button" id="save-button" data-edit-action="save" data-add-id="true"><span
                                uk-icon="check"></span> Сохранить и закрыть
                    </button>
                </li>
                <?php if (!empty($options['media'])) { ?>
                    <li><?= ImageSelector::Widget([
                            'selectType' => ImageSelector::SELECT_MULTIPLE,
                            'name' => 'img',
                            'buttonText' => 'Фотографии',
                            'buttonClass' => 'uk-button',
                            'galleryId' => $options['galleryId'],
                        ]) ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li>
                    <button class="uk-button uk-text-warning" id="cancel-button"><span uk-icon="arrow-left"></span>Отмена
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</header>
