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
 * @var Media[] $items
 */

use app\models\Media;

?>


<?php if(!empty($items)) {?>
    <ul id="sortable-gallery-photos" class="uk-grid-small" uk-sortable="handle: .uk-sortable-handle" uk-grid>
        <?php foreach ($items as $key => $item) {?>
            <li>
                <div class="photo-preview" uk-lightbox>
                    <span class="uk-sortable-handle" uk-icon="icon: table"></span>
                    <span class="edit-in-gallery" data-item-id="<?=$item->id?>" uk-icon="icon: file-edit"></span>
                    <a href="<?=$item->url?>" data-item-id="<?=$item->id?>" class="view-in-gallery"><span uk-icon="icon: eye"></span></a>
                    <span class="uk-text-danger remove-from-gallery" data-item-id="<?=$item->id?>" uk-icon="icon: trash"></span>
                    <img src="<?=$item->url_thumbnail?>" alt="<?=$item->title?>" class="sortable-thumb" />
                    <input type="hidden" value="<?=$item->id?>" name="media[<?=$key?>][id]" />
                    <input type="hidden" value="<?=$key + 1?>" name="media[<?=$key?>][ordering]" class="ordering-field-input" />
                    <?=$item->title ? $item->title : pathinfo($item->path, PATHINFO_BASENAME) ?>
                </div>
            </li>
        <?php } ?>
    </ul>
<?php } ?>