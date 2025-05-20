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
 * @var Folder[] $folders
 * @var Media[]  $media
 * @var Folder   $currentFolder
 * @var bool     $allowMultiSelect
 * @var array    $selectedItems
 */

use app\models\Folder;
use app\models\Media;

?>
<div id="folders-breadcrumb">
    <?php foreach (Folder::getChain($currentFolder->id) as $folder) { ?>
        <a class="uk-link-reset" href="#" data-folder-id="<?= $folder->id ?>"><span
                    uk-icon="<?= $folder->id == 0 ? 'server' : 'folder' ?>"></span> <?= $folder->title ?></a>/
    <?php } ?>
</div>
<div id="files-aria" class="uk-flex uk-flex-left uk-flex-wrap">
    <?php foreach ($folders as $folder) { ?>
        <div>
            <div class="card">
                <label for="folder-item-<?= $folder->id ?>" class="media-thumb-wrapper folder-wrapper">
                    <span uk-icon="icon: folder; ratio: 4"></span>
                    <?php if ($allowMultiSelect) { ?>
                        <input type="checkbox" value="<?= $folder->id ?>" id="folder-item-<?= $folder->id ?>"
                               name="ids[]" data-item-type="folder" class="uk-checkbox">
                    <?php } else { ?>
                        <input type="radio" value="<?= $folder->id ?>" id="folder-item-<?= $folder->id ?>" name="ids"
                               data-item-type="folder" class="uk-checkbox">
                    <?php } ?>
                </label>
                <div class="card-header"><a class="uk-link-reset" href="#"
                                            data-folder-id="<?= $folder->id ?>"><?= $folder->title ?> <span
                                uk-icon="file-edit"></span></a></div>
            </div>
        </div>
    <?php } ?>
    <?php foreach ($media as $item) { ?>
        <div>
            <div class="card<?= in_array($item->id, $selectedItems) ? ' selected' : ''; ?>">
                <label for="media-item-<?= $item->id ?>" class="media-thumb-wrapper">
                    <img src="<?= $item->url_thumbnail ?>" alt="<?= $item->alt ?>" title="<?= $item->title ?>"/>
                    <?php if ($allowMultiSelect) { ?>
                        <input type="checkbox" value="<?= $item->id ?>" id="media-item-<?= $item->id ?>" name="ids[]"
                               data-item-type="media"
                               class="uk-checkbox"<?= in_array($item->id, $selectedItems) ? ' checked' : ''; ?>>
                    <?php } else { ?>
                        <input type="radio" value="<?= $item->id ?>" id="media-item-<?= $item->id ?>" name="ids"
                               data-item-type="media" class="uk-checkbox">
                    <?php } ?>
                </label>
                <div class="card-header">
                    <span class="filename"><?= pathinfo($item->path, PATHINFO_BASENAME) ?></span>
                    <div>
                        <a class="uk-link-reset" href="#" data-item-id="<?= $item->id ?>"><span
                                    uk-icon="file-edit"></span></a>
                        <span uk-lightbox><a class="uk-link-reset" href="<?= $item->url ?>"
                                             id="lightbox-<?= $item->id ?>"><span uk-icon="eye"></span></a></span>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
