<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\assets\Admin\AdminAsset;
use app\models\Page;
use app\widgets\AdminPagination;
use app\widgets\AdminToolbar\ListToolbar;
use app\widgets\Status\Status;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$adminUrl = env('ADMIN_URL');

/** @var Page[] $items */
/** @var Pagination $pagination */

AdminAsset::register($this);
?>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin([
            'id' => 'admin-form',
            'action' => '/' . $adminUrl . '/page',
            'method' => 'POST',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>


        <?=ListToolbar::widget(); ?>
        <table class="uk-table uk-table-striped uk-table-hover uk-table-middle">
            <thead>
            <tr>
                <th scope="col">#&nbsp;&nbsp;&nbsp;<?php echo Html::checkbox(null, false, [
                        'label' => 'Все',
                        'class' => 'check-all uk-checkbox',
                    ]); ?></th>
                <th scope="col">Название</th>
                <th scope="col">Публикация</th>
            </tr>
            </thead>
            <?php foreach($items as $key => $item) {?>
                <tr>
                    <td>
                        <div class="text-center">
                            #<?=$item->id . ' ' . Html::checkbox("ids[{$key}]", false,
                                    [
                                        'label' => null,
                                        'id' => 'ids-' . $key,
                                        'value' => $item->id,
                                        'class' => 'uk-checkbox list-selector'
                                    ]); ?>
                        </div>
                    </td>
                    <td>
                        <a href="/<?=$adminUrl;?>/page/edit/<?=$item->id ?>"><?=$item->title ?></a>
                        <div class="uk-text-small"><?=$item->subtitle ?></div>
                    </td>
                    <td>
                        <div class="text-center">
                            <?=Status::widget([
                                'options' => [
                                    'published' => $item->published,
                                    'row' => $key
                                ]
                            ]); ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            <tfoot>
            <tr>
                <th colspan="3">
                    <?=AdminPagination::widget(['options' => ['pagination' => $pagination]]) ?>
                </th>
            </tr>
            </tfoot>
        </table>
        <input name="action" value="" type="hidden">
        <?php ActiveForm::end(); ?>

    </div>
</div>

