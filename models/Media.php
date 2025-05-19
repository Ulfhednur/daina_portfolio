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
use app\services\FileService;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;

/**
 * Class Media
 *
 * @property int    $id
 * @property int    $folder_id
 * @property string $title
 * @property string $title_en
 * @property string $alt
 * @property string $alt_en
 * @property string $description
 * @property string $description_en
 * @property string $path
 * @property string $thumbnail
 * @property string $preview
 * @property string $url
 * @property string $url_thumbnail
 * @property string $url_preview
 */
class Media extends ActiveRecord
{
    protected bool $disableTransactions = false;

    public string $imageContent;

    public string $imageMime;

    protected array $translatable = [
        'description', 'title', 'alt',
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
        return 'media';
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['description', 'title', 'alt'], 'string', 'max' => 64],
            [['description_en', 'title_en', 'alt_en'], 'string', 'max' => 64],
            [['path', 'thumbnail', 'preview', 'url', 'url_thumbnail', 'url_preview'], 'string', 'max' => 1024],
            [['folder_id'], 'integer'],
            [['path', 'thumbnail', 'preview'], 'required'],
            ['path', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'id',
            'folder_id' => 'Каталог',
            'title' => 'Аттрибут title',
            'title_en' => 'Аттрибут title (En)',
            'alt' => 'Аттрибут alt',
            'alt_en' => 'Аттрибут alt (En)',
            'description' => 'Краткое описание (64 символа)',
            'description_en' => 'Краткое описание (64 символа) (En)',
            'path' => 'Имя файла в каталоге',
        ];
    }

    /**
     * @inheritDoc
     */
    public function load($data, $formName = null): bool
    {
        if (!empty($data['fileName'])) {
            $info = pathinfo($data['fileName']);
            $fileName = mb_substr(BaseInflector::slug($info['filename']), 0, 19) . '.' . $info['extension'];
            unset($data['fileName']);
            if (parent::load($data, $formName) && $fileName) {
                $this->path = implode('/', array_column(Folder::getChain($data['folder_id']), 'title')) . '/' . $fileName;
                $subPaths = FileService::prepareThumbPaths($this->path);
                $this->thumbnail = $subPaths['thumb'];
                $this->preview = $subPaths['preview'];
                return true;
            }

            return false;
        }

        return parent::load($data, $formName);
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && !empty($this->imageContent) && !empty($this->imageMime)) {
            $file = new FileService();
            $urls = $file->uploadImage($this->path, $this->imageContent, $this->imageMime);
            foreach ($urls as $key => $value) {
                $this->$key = $value;
            }
            $this->updateAttributes(['url', 'url_thumbnail', 'url_preview']);
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeDelete(): bool
    {
        $file = new FileService();
        $file->removeImage($this->path);

        return parent::beforeDelete();
    }

    public function getGalleryItems(): ActiveQuery
    {
        return $this->hasMany(MediaGallery::class, ['media_id' => 'id']);
    }

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        if ($name == 'parent_id') {
            $name = 'folder_id';
        }
        $lang = langHelper::getCurrentLang();
        if (!langHelper::isLangDefault() && in_array($name, $this->translatable)) {
            $name .= '_' . $lang;
        }
        return parent::__get($name);
    }
}