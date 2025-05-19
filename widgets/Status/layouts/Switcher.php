<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

/** @var bool $status */

?>

<div class="publish-switcher-wrapper">
    <label>Опубликовано</label>
    <div class="publish-switcher">
        <label for="published-true"<?=$status ? ' class="active"' : ''?>><input id="published-true" type="radio" class="uk-radio" name="published" value="1"<?=$status ? ' selected' : ''?>>Да</label>
        <label for="published-false"<?=!$status ? ' class="active"' : ''?>><input id="published-false" type="radio" class="uk-radio" name="published" value="0"<?=!$status ? ' selected' : ''?>>Нет</label>
    </div>
</div>
