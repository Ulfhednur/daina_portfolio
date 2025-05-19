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

use app\helpers\langHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\BaseInflector;

/**
 * Abstract class Item
 *
 * @property int        $id
 * @property string     $item_type
 * @property int        $image_id
 * @property int        $published
 * @property string     $alias
 * @property string     $title
 * @property string     $subtitle
 * @property string     $description
 * @property string     $seo_title
 * @property string     $seo_description
 * @property int        $ordering
 * @property string     $created_date
 *
 * @property-read Media $image
 */
abstract class Item extends ActiveRecord
{
    /** Подключаем сортировку */
    use OrderingTrait;

    /** Типы записей */
    const string ITEM_TYPE_PAGE = 'page';
    const string ITEM_TYPE_POST = 'post';
    const string ITEM_TYPE_GALLERY = 'gallery';

    /** @var string тип текущей записи */
    protected static string $itemType;

    /** @var bool флаг отключения транзакций, для обработки нескольких записей в одной транзакции */
    protected bool $disableTransactions = false;

    /** @var string[] Переводимые поля */
    protected array $translatable = [
        'title', 'subtitle', 'description', 'seo_description', 'seo_title', 'subtitle',
    ];

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
        return 'items';
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['title', 'alias', 'subtitle'], 'string', 'max' => 255],
            [['title_en', 'alias', 'subtitle_en'], 'string', 'max' => 255],
            [['description', 'seo_description', 'seo_title', 'subtitle'], 'string'],
            [['description_en', 'seo_description_en', 'seo_title_en', 'subtitle_en'], 'string'],
            [['image_id', 'published', 'ordering'], 'integer'],
            ['published', 'in', 'range' => [0, 1]],
            [['title'], 'required'],
            ['alias', 'filter', 'filter' => function($value) {
                if (empty($value)) {
                    $value = $this->title;
                }
                return BaseInflector::slug($value);
            }],
            [['alias'], 'unique'],
            ['created_date', 'default', 'value' => (new \DateTime())->setTimezone(new \DateTimeZone(env("TIMEZONE")))->format('Y-m-d H:i:s')],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function find(): ActiveQuery
    {
        $query = parent::find()->andWhere(['item_type' => static::$itemType]);

        if (!langHelper::isLangDefault()) {
            $title = 'title_' . langHelper::getCurrentLang();
            $query->andWhere(['!=', $title, '']);
        }
        return $query;
    }

    /**
     * @inheritDoc
     */
    public function isTransactional($operation): bool
    {
        if ($this->disableTransactions) {
            return false;
        }
        return parent::isTransactional($operation);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'id',
            'item_type' => 'Тип записи',
            'image_id' => 'Изображение записи',
            'published' => 'Публикация',
            'alias' => 'Алиас',
            'title' => 'Заголовок',
            'subtitle' => 'Подзаголовок',
            'description' => 'Описание',
            'seo_title' => 'SEO заголовок',
            'seo_description' => 'SEO Описание',
            'title_en' => 'Заголовок (En)',
            'subtitle_en' => 'Подзаголовок (En)',
            'description_en' => 'Описание (En)',
            'seo_title_en' => 'SEO заголовок (En)',
            'seo_description_en' => 'SEO Описание (En)',
            'ordering' => 'Порядок сортировки',
            'created_date' => 'Дата создания',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['item_type'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['item_type'],
                ],
                'value' => function() {
                    return static::$itemType;
                },
            ],
        ];
    }

    /**
     * Поиск
     *
     * @param string|null $search
     *
     * @return ActiveQuery
     */
    public static function Search(?string $search)
    {
        $query = static::find();
        if ($search) {
            $query->where(['like', 'title', $search]);
        }
        return $query;
    }

    /**
     * Магия. Подцепляем суффикс языка к полю
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        $lang = langHelper::getCurrentLang();
        if (!langHelper::isLangDefault() && in_array($name, $this->translatable)) {
            $name .= '_' . $lang;
        }

        return parent::__get($name);
    }
}