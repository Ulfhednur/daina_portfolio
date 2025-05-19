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
 * Каталоги файлов. Виртуальные, для удобства организации
 *
 * @property int        $id
 * @property int        $parent_id
 * @property string     $title
 * @property Folder[]   $children
 *
 * @property-read Media[] $files
 */
class Folder extends ActiveRecord
{
    /** @var array $children вложенные каталоги */
    public array $children = [];

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
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'id',
            'parent_id' => 'Родитель',
            'title' => 'Название',
            'children' => 'Вложение',
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

        foreach ($this->getChildren()->each() as $subFolder) {
            /** @var Folder $media */
            $subFolder->delete();
        }

        return parent::beforeDelete();
    }

    /**
     * Файлы каталога
     *
     * @return ActiveQuery
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(Media::class, ['folder_id' => 'id']);
    }

    /**
     * Вложенные каталоги
     *
     * @return ActiveQuery
     */
    public function getChildren(): ActiveQuery
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * Путь к каталогу
     *
     * @param int $id
     *
     * @return array
     */
    public static function getChain(int $id): array
    {
        $items = [];
        do {
            $item = self::findOne(['id' => $id]);
            if (!empty($item)) {
                $items[] = $item;
                $id = $item->parent_id;
            } else {
                $id = 0;
            }
        } while ($id != 0);
        $items[] = (object) ['id' => 0,
            'parent_id' => 0,
            'title' => ''
        ];
        return array_reverse($items);
    }

    /**
     * Дерево каталогов
     *
     * @return object[]
     */
    public static function getTree(): array
    {
        return [
            (object) [
                'id' => 0,
                'parent_id' => 0,
                'title' => 'Корень',
                'children' => self::formTree(static::find()->orderBy(['parent_id' => SORT_ASC])->all()),
            ]
        ];
    }

    /**
     * Преобразование списка в дерево.
     * Лучше рекурсивная функция, чем рекурсивный запрос...
     *
     * @param array $rows
     * @param int   $parentId
     *
     * @return array
     */
    protected static function formTree(array $rows, int $parentId = 0): array
    {
        $items = [];
        foreach ($rows as $item) {
            /** @var static $item */
            if ($item->parent_id == $parentId) {
                $item->children = self::formTree($rows, $item->id);
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Корневой каталог
     *
     * @return \stdClass
     */
    public static function getRoot(): \stdClass
    {
        return (object) [
            'id' => 0,
            'parent_id' => 0,
            'title' => 'Корень',
            'children' => static::find()->where(['parent_id' => 0])->orderBy(['title' => SORT_ASC])->all(),
        ];
    }
}