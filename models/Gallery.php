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

use Yii;
use yii\db\ActiveQuery;

/**
 * class Gallery
 *
 * @property int $id
 * @property string $item_type
 * @property int $image_id
 * @property int $published
 * @property string $alias
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property int $ordering
 * @property string $created_date
 *
 * @property-read MediaGallery[] $galleryItems
 * @property-read Media[]        $media
 */
class Gallery extends Item
{
    protected array $media = [];

    protected static string $itemType = parent::ITEM_TYPE_GALLERY;

    /**
     * @inheritDoc
     */
    public function load($data, ?string $formName = null): bool
    {
        if (parent::load($data, $formName) && !empty($data['media'])) {
            $this->media = $data['media'];
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

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->getGalleryItems()->each() as $galleryItem) {
                $galleryItem->delete();
            }
            $i = 1;
            if (!empty($this->media)) {
                foreach ($this->media as $mediaId => $ordering) {
                    if (!$ordering || $ordering < $i) {
                        $ordering = $i;
                        $i++;
                    } else {
                        $i = $ordering;
                    }

                    $galleryItem = new MediaGallery();
                    $galleryItem->item_id = $this->id;
                    $galleryItem->ordering = $ordering;
                    $galleryItem->media_id = $mediaId;
                    $galleryItem->save();
                }
                $galleryItem->reorder(false, ['item_id' => $this->id]);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getGalleryItems(): ActiveQuery
    {
        return $this->hasMany(MediaGallery::class, ['item_id' => 'id']);
    }

    public function getMedia(): ActiveQuery
    {
        return $this->hasMany(Media::class, ['id' => 'media_id'])
                    ->viaTable('media_gallery', ['item_id' => 'id']);
    }
}