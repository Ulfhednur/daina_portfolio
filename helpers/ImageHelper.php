<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace helpers;

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
                $image->cropThumbnailImage(env($type), env($type));
                break;
            case self::RESIZE_TYPE_PREVIEW:
                $imageProps = $image->getImageGeometry();
                $width = $imageProps['width'];
                $height = $imageProps['height'];
                if ($width > $height) {
                    $newHeight = env($type);
                    $newWidth = (env($type) / $height) * $width;
                } else {
                    $newWidth = env($type);
                    $newHeight = (env($type) / $width) * $height;
                }
                $image->thumbnailImage($newWidth, $newHeight, true);
                break;
        }

        $image->setImageFormat('webp');
        return $image->getImageBlob();
    }
}