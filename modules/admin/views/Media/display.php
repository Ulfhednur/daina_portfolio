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
 * @var Folder[]     $folders
 * @var Media[]      $media
 * @var Folder       $currentFolder
 * @var yii\web\View $this
 * @var string       $selectType
 * @var string       $allowMultiSelect
 * @var array        $selectedItems
 */

use app\assets\FileManager\FileManagerAsset;
use app\models\Folder;
use app\models\Media;
use app\widgets\ImageSelector\ImageSelector;

FileManagerAsset::register($this);

?>
<ul uk-tab="#media-manager-tabs" class="media-manager-tabs">
    <li class="uk-active"><a href="">Файлы</a></li>
    <li><a href="">Загрузка</a></li>
</ul>
<button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
<div class="uk-modal-body">
    <div class="uk-switcher uk-margin" id="media-manager-tabs">
        <div>
            <header id="admin-tool-panel" class="uk-section-muted">
                <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
                    <div class="uk-navbar-left">
                        <ul class="uk-navbar-nav">
                            <li>
                                <button class="uk-button" id="folder-create"><span uk-icon="folder"></span> Создать
                                    каталог
                                </button>
                            </li>
                            <?php if ($selectType != ImageSelector::SELECT_NONE) { ?>
                                <li>
                                    <button class="uk-button uk-button-primary" id="media-select"
                                            type="button"><?= $selectType == ImageSelector::SELECT_SINGLE ? 'Выбрать' : 'Добавить' ?></button>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="uk-navbar-right">
                        <ul class="uk-navbar-nav">
                            <li>
                                <button class="uk-button uk-button-danger" id="btn-remove"><span uk-icon="trash"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <div uk-grid class="uk-grid-small uk-grid-divider" id="main-working-area">
                <div class="uk-width-1-6" id="folders-panel">
                    <?= $this->render('@adminViews/layouts/folderPanel', ['folders' => $folders, 'level' => 0, 'currentFolder' => $currentFolder]); ?>
                </div>
                <div class="uk-width-2-3">
                    <div id="files-panel">
                        <?= $this->render('@adminViews/layouts/filesPanel', [
                            'folders' => $currentFolder->children,
                            'media' => $media,
                            'currentFolder' => $currentFolder,
                            'allowMultiSelect' => $allowMultiSelect,
                            'selectedItems' => $selectedItems
                        ]); ?>
                    </div>
                </div>
                <div class="uk-width-1-6" id="info-panel">
                    &nbsp;
                </div>
            </div>
        </div>
        <div>
            <div class="uk-container uk-container-expand">
                <div id="drag-drop-area" class="uk-background-muted">
                    <p><span><span uk-icon="cloud-upload"
                                   class="uk-text-primary"></span>&nbsp;Перетащите сюда файлы</span></p>
                </div>
                <div id="file-previews"></div>
            </div>
        </div>
    </div>
    <div id="spinner-modal">
        <div class="spinner-overlay">
            <div uk-spinner="ratio: 4"></div>
        </div>
    </div>
</div>