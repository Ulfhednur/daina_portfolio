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
 * @var string $value
 * @var string $name
 */

use app\widgets\TemplateSelector\GalleryTemplateSelector;

$typeLabels = GalleryTemplateSelector::getTypeNames();
?>

<div class="form-group field-settings-template uk-margin-top">
    <div>Шаблон</div>
    <div class="uk-grid-small" uk-grid>
        <?php foreach (GalleryTemplateSelector::getTypes() as $type) { ?>
            <div>
                <label for="tmpl-<?= $type ?>" class="label-<?= $type ?>" uk-tooltip="<?= $typeLabels[$type] ?>">
                    <input id="tmpl-<?= $type ?>" type="radio" name="<?= $name ?>"
                           value="<?= $type ?>"<?= $type == $value ? ' checked' : '' ?> class="uk-checkbox">
                </label>
            </div>
        <?php } ?>
    </div>
</div>
