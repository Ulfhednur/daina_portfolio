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
 * @var string          $buttonText
 * @var string          $ImageSelectorMultiselect
 * @var string          $ImageSelectorInputName
 * @var int             $galleryId
 * @var string          $buttonClass
 */

use app\widgets\ImageSelector\ImageSelector;

?>
<button class="<?=$buttonClass?> image-selector-modal-opener" type="button"
        data-select-type="<?=$ImageSelectorMultiselect?>"
        data-selector-input-name="<?=$ImageSelectorInputName?>"
        data-gallery-id="<?=$galleryId?>"><span uk-icon="image"></span> <?=$buttonText?></button>
<?php if(!ImageSelector::$rendered) {?>
<div id="image-selector-modal" class="uk-modal-full">
    <div class="uk-modal-dialog">

    </div>
</div>
<?php } ?>