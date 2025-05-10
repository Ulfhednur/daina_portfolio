<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

/** @var Folder[] $folders */
/** @var int      $level */
/** @var Folder   $currentFolder */

use app\models\Folder;

?>
<ul class="uk-list level-<?=$level?>">
    <?php foreach ($folders as $folder) {?>
        <?php
            $class = [];
            if ($level) {
                $class[] = 'child-level';
            }
            if ($folder->id == $currentFolder->id) {
                $class[] = 'uk-active';
            }

            if ($class) {
                $class = 'class="' . implode(' ', $class) . '" ';
            } else {
                $class = '';
            }
        ?>
        <li <?=$class?>data-folder-id="<?=$folder->id?>">
            <a class="uk-link-reset" href="#" data-folder-id="<?=$folder->id?>">
                <span uk-icon="icon: folder; ratio: 1"></span> <?=$folder->title?>
            </a>
            <?php if (!empty($folder->children)) {?>
                <?=$this->render('@adminViews/layouts/folderPanel', ['folders' => $folder->children, 'level' => $level + 1, 'currentFolder' => $folder]); ?>
            <?php } ?>
        </li>
    <?php } ?>
</ul>
