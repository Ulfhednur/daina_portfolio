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
use yii\helpers\BaseInflector;

/**
 * Class Media
 *
 * @property int    $id
 * @property int    $parent_id
 * @property string $title
 *
 * @property-read Media[] $files
 */
class Folder extends ActiveRecord
{
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
        return 'media_folders';
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['title', 'string', 'max' => 255],
            ['parent_id', 'integer'],
            ['title', 'filter', 'filter' => function ($value) {
                return BaseInflector::slug($value);
            }],
            ['title', 'required'],
            ['title', 'unique', 'targetAttribute' => ['title', 'parent_id']],
        ];
    }

    /**
     * @inheritDoc
     */
    public function beforeDelete(): bool
    {
        foreach ($this->getFiles()->each() as $media) {
            /** @var Media $media */
            $media->delete();
        }

        return parent::beforeDelete();
    }

    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(Media::class, ['folder_id' => 'id']);
    }

    public static function getChain(int $id): string
    {
        $titles = [];
        do {
            $item = self::findOne(['id' => $id]);
            $titles[] = $item->title;
            $id = $item->parent_id;
        } while ($id != 0);

        return '/' . implode('/', $titles);
    }
}