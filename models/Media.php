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

use services\FileService;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;

/**
 * Class Media
 *
 * @property int    $id
 * @property int    $folder_id
 * @property string $title
 * @property string $alt
 * @property string $description
 * @property string $path
 * @property string $thumbnail
 * @property string $preview
 */
class Media extends ActiveRecord
{
    protected bool $disableTransactions = false;

    public string $imageContent;

    public string $imageMime;

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
            [['title', 'alt', 'subtitle'], 'string', 'max' => 64],
            [['description', 'path', 'thumbnail', 'preview'], 'string', 'max' => 1024],
            [['folder_id', 'ordering'], 'integer'],
            [['path', 'thumbnail', 'preview'], 'required'],
            ['path', 'unique'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function load($data, ?string $formName = null): bool
    {
        $data['fileName'] = BaseInflector::slug($data['fileName']);
        if (parent::load($data, $formName) && !empty($data['fileName'])) {
            $this->path = Folder::getChain($data['folderId']) . '/' . $data['fileName'];
            $subPaths = FileService::prepareThumbPaths($this->path);
            $this->thumbnail = $subPaths['thumb'];
            $this->preview = $subPaths['preview'];
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && !empty($this->imageContent) && !empty($this->imageMime)) {
            $file = new FileService();
            $file->uploadImage($this->path, $this->imageContent, $this->imageMime);
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
}