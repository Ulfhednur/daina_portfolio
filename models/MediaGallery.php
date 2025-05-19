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
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\BaseInflector;

/**
 * Class MediaGallery
 * Отношение галерея - картинка, многие ко многим, с сортировкой
 *
 * @property int        $item_id
 * @property int        $ordering
 * @property int        $media_id
 *
 * @property-read Media $media
 */
class MediaGallery extends ActiveRecord
{
    /** Подключаем сортировку */
    use OrderingTrait;

    /**
     * @inheritDoc
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'media_gallery';
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['item_id', 'ordering', 'media_id'], 'integer'],
            ['ordering', 'filter', 'filter' => function ($value) {
                if ($value == 0) {
                    return (new Query())
                            ->from('media_gallery')
                            ->where(['item_id' => $this->item_id])
                            ->max('ordering') + 1;
                }
                return $value;
            }],
            [['item_id', 'ordering', 'media_id'], 'required'],
            ['media_id', 'unique', 'targetAttribute' => ['media_id', 'item_id']],
        ];
    }

    /**
     * Удаление связи с пересортировкой
     *
     * @return void
     * @throws \Throwable|\yii\db\Exception|\yii\db\StaleObjectException
     */
    public function delete(): void
    {
        $cond = [
            'item_id' => $this->item_id
        ];
        parent::delete();
        self::reorder(true, $cond);
    }

    /**
     * Получение связанных медиа
     *
     * @return ActiveQuery
     */
    public function getMedia(): ActiveQuery
    {
        return $this->hasOne(Media::class, ['id' => 'media_id']);
    }
}