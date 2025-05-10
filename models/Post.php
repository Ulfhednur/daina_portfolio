<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\models;

use yii\db\ActiveQuery;

class Post extends Item
{
    protected static string $itemType = parent::ITEM_TYPE_POST;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [['image_id'], 'exist', 'targetClass' => Media::class, 'targetAttribute' => 'id'],
                ['created_date', 'filter', 'filter' => function ($value) {
                    if (preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $value)) {
                        return \DateTime::createFromFormat('d.m.Y H:i:s', $value)->format('Y-m-d H:i:s');
                    }
                    return $value;
                }],
                ['created_date', 'required'],
                ['created_date', 'date', 'format' => 'php:Y-m-d H:i:s'],
            ]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function getImage(): ActiveQuery
    {
        return $this->hasOne(Media::class, ['id' => 'image_id']);
    }
}