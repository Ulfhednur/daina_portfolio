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
 * @property int    $item_id
 * @property int    $ordering
 * @property int    $media_id
 */
class MediaGallery extends ActiveRecord
{
    use Orderings;

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
            [['item_id', 'ordering', 'media_id'], 'required'],
            ['ordering', 'unique', 'targetAttribute' => ['ordering', 'item_id']],
        ];
    }
}