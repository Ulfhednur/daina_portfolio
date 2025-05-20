<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\models\Page;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var Page $item
 */

$this->title = $item->title;
$this->params['lang_path'] = ['site/about', 'language' => 'ru'];
?>
<section class="uk-section uk-section-primary">
    <div class="uk-container uk-container-xlarge">
        <h1 class="uk-h1 uk-text-center"><?= Html::encode($this->title) ?></h1>
        <?= $item->description ?>
    </div>
</section>