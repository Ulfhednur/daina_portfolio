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
use yii\db\Query;

/**
 * class Gallery
 *
 * @property int                 $id
 * @property string              $item_type
 * @property int                 $image_id
 * @property int                 $published
 * @property string              $alias
 * @property string              $title
 * @property string              $title_en
 * @property string              $subtitle
 * @property string              $subtitle_en
 * @property string              $description
 * @property string              $description_en
 * @property string              $seo_title
 * @property string              $seo_title_en
 * @property string              $seo_description
 * @property string              $seo_description_en
 * @property int                 $ordering
 * @property string              $created_date
 *
 * @property-read MediaGallery[] $galleryItems
 * @property-read Media[]        $media
 * @property-read Media          $image
 */
class Gallery extends Item
{
    protected array $mediaItems = [];

    protected static string $itemType = parent::ITEM_TYPE_GALLERY;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [['image_id'], 'exist', 'targetClass' => Media::class, 'targetAttribute' => 'id'],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function load($data, $formName = null): bool
    {
        if (parent::load($data, $formName)) {
            if (!empty($data['media'])) {
                $this->mediaItems = $data['media'];
            }
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
            if (!empty($this->mediaItems)) {
                foreach ($this->mediaItems as $mediaItem) {
                    if (!$mediaItem['ordering'] || $mediaItem['ordering'] < $i) {
                        $mediaItem['ordering'] = $i;
                        $i++;
                    } else {
                        $i = $mediaItem['ordering'];
                    }

                    $galleryItem = new MediaGallery();
                    $galleryItem->item_id = $this->id;
                    $galleryItem->ordering = $mediaItem['ordering'];
                    $galleryItem->media_id = $mediaItem['id'];
                    $galleryItem->save();
                }
                MediaGallery::reorder(false, ['item_id' => $this->id]);
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
            ->via('galleryItems')
            ->joinWith(['galleryItems' => function(ActiveQuery $query) {
                $query->orderBy('media_gallery.ordering');
            }]);
    }

    public function getImage(): ActiveQuery
    {
        return $this->hasOne(Media::class, ['id' => 'image_id']);
    }
}