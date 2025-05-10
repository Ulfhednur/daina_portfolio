<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\helpers;

use Imagick;
use ImagickException;

class ImageHelper
{
    const string RESIZE_TYPE_THUMB = 'IMAGE_THUMB_SIZE';
    const string RESIZE_TYPE_PREVIEW = 'IMAGE_PREVIEW_SIZE';

    /**
     * @param string $content
     * @param string $type
     * @return string
     * @throws ImagickException
     */
    public static function resize(string $content, string $type): string
    {
        $image = new Imagick();
        $image->readImageBlob($content);
        switch ($type) {
            case self::RESIZE_TYPE_THUMB:
                $image->cropThumbnailImage((int) env($type), (int) env($type));
                break;
            case self::RESIZE_TYPE_PREVIEW:
                $imageProps = $image->getImageGeometry();
                $width = $imageProps['width'];
                $height = $imageProps['height'];
                if ($width > $height) {
                    $newHeight = (int) env($type);
                    $newWidth = (int) (((int) env($type) / $height) * $width);
                } else {
                    $newWidth = (int) env($type);
                    $newHeight = (int) (((int) ($type) / $width) * $height);
                }
                $image->thumbnailImage($newWidth, $newHeight, true);
                break;
        }

        $image->setImageFormat('webp');
        return $image->getImageBlob();
    }
}