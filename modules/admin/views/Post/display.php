<?php
declare(strict_types=1);

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use app\assets\admin\AdminAsset;
use app\models\Post;
use app\widgets\AdminPagination;
use app\widgets\AdminToolbar\ListToolbar;
use app\widgets\Status\Status;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$adminUrl = env('ADMIN_URL');
/**
 * @var Post[]     $items
 * @var Pagination $pagination
 * @var int        $pageSize
 */

AdminAsset::register($this);
?>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin([
            'id' => 'admin-form',
            'action' => '/' . $adminUrl . '/post',
            'method' => 'POST',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>


        <?= ListToolbar::widget([
            'options' => [
                'media' => true,
            ],
        ]); ?>
        <table class="uk-table uk-table-striped uk-table-hover uk-table-middle">
            <thead>
            <tr>
                <th scope="col">#&nbsp;&nbsp;&nbsp;<?php echo Html::checkbox(null, false, [
                        'label' => 'Все',
                        'class' => 'check-all uk-checkbox',
                    ]); ?></th>
                <th scope="col">Изображение</th>
                <th scope="col">Название</th>
                <th scope="col">Дата</th>
                <th scope="col">Публикация</th>
            </tr>
            </thead>
            <tbody uk-sortable="handle: .uk-sortable-handle">
            <?php foreach ($items as $key => $item) { ?>
                <tr>
                    <td>
                        <div class="text-center">
                            #<?= $item->id . ' ' . Html::checkbox("ids[{$key}]", false,
                                [
                                    'label' => null,
                                    'id' => 'ids-' . $key,
                                    'value' => $item->id,
                                    'class' => 'uk-checkbox list-selector',
                                ]); ?>
                            &nbsp;<span class="uk-sortable-handle" uk-icon="icon: table"></span>
                            <input type="hidden" name="items[<?= $item->id ?>][id]" value="<?= $item->id ?>"
                                   class="id-field-input"/>
                            <input type="hidden" name="items[<?= $item->id ?>][ordering]" value="<?= $item->ordering ?>"
                                   class="ordering-field-input"/>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($item->image_id)) { ?>
                            <img src="<?= $item->image->url_thumbnail; ?>" alt="Item image" class="list-preview"/>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="/<?= $adminUrl; ?>/post/edit/<?= $item->id ?>"><?= $item->title ?></a>
                        <div class="uk-text-small"><?= $item->subtitle ?></div>
                    </td>
                    <td>
                        <?= (new DateTime($item->created_date))->format('d.m.Y H:i:s') ?>
                    </td>
                    <td>
                        <?= Status::widget([
                            'options' => [
                                'published' => $item->published,
                                'row' => $key,
                            ],
                        ]); ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4">
                    <?= AdminPagination::widget(['options' => ['pagination' => $pagination]]) ?>
                </th>
                <th>
                    Строк на странице <select name="pageSize" class="uk-select">
                        <option value="50"<?= $pageSize == 50 ? ' selected' : '' ?>>50</option>
                        <option value="150"<?= $pageSize == 150 ? ' selected' : '' ?>>150</option>
                        <option value="500"<?= $pageSize == 500 ? ' selected' : '' ?>>500</option>
                        <option value="50000"<?= $pageSize == 50000 ? ' selected' : '' ?>>50 000</option>
                    </select>
                </th>
            </tr>
            </tfoot>
        </table>
        <input name="action" value="" type="hidden">
        <?php ActiveForm::end(); ?>

    </div>
</div>

