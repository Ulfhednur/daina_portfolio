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

/**
 * Запись блога
 */
class Post extends Item
{
    /**
     * @inheritDoc
     */
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
                ['created_date', 'filter', 'filter' => function($value) {
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
     * Преобразуем дату в российский формат
     *
     * @return void
     */
    public function afterFind(): void
    {
        if (!empty($this->created_date)) {
            $this->created_date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->created_date)->format('d.m.Y H:i:s');
        }
        parent::afterFind();
    }

    /**
     * Получение картинки поста
     *
     * @return ActiveQuery
     */
    public function getImage(): ActiveQuery
    {
        return $this->hasOne(Media::class, ['id' => 'image_id']);
    }
}