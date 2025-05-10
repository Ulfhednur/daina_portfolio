<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace services;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use helpers\ImageHelper;

class FileService
{
    private S3Client $s3Client;

    private string $bucket;

    public function __construct($settings = [])
    {
        $this->s3Client = new S3Client([
            'bucket_endpoint' => true,
            'version' => 'latest',
            'region' => env('S3_REGION'),
            'endpoint' => env('S3_HOST'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => env('S3_ID'),
                'secret' => env('S3_KEY'),
            ]
        ]);

        $this->bucket = env('S3_BUCKET');
    }

    protected function uploadFile(string $destination, string $content, string $contentType): void
    {
        $this->s3Client->putObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $this->bucket . $destination,
                'Body' => $content,
                'ACL' => 'public',
                'ContentType' => $contentType,
            ]
        );
    }

    public static function prepareThumbPaths(string $destination):array
    {
        $pathInfo = pathinfo($destination);

        return [
            'thumb' => $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'] . '.webp',
            'preview' => $pathInfo['dirname'] . '/preview_' . $pathInfo['basename'] . '.webp',
        ];
    }

    public function uploadImage(string $destination, string $content, string $contentType): void
    {
        $destinations = self::prepareThumbPaths($destination);
        $this->uploadFile($destination, $content, $contentType);
        $this->uploadFile($destinations['thumb'], ImageHelper::resize($content, ImageHelper::RESIZE_TYPE_THUMB), 'image/webp');
        $this->uploadFile($destinations['preview'], ImageHelper::resize($content, ImageHelper::RESIZE_TYPE_PREVIEW), 'image/webp');
    }

    public function recreateThumbs(string $destination): void
    {
        $destinations = self::prepareThumbPaths($destination);

        $image = $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $this->bucket . $destination,
        ]);
        $this->uploadFile($destinations['thumb'], ImageHelper::resize($image->get('body'), ImageHelper::RESIZE_TYPE_THUMB), 'image/webp');
        $this->uploadFile($destinations['preview'], ImageHelper::resize($image->get('body'), ImageHelper::RESIZE_TYPE_PREVIEW), 'image/webp');
    }

    public function removeImage(string $destination): void
    {
        $destinations = self::prepareThumbPaths($destination);

        foreach([$destination, $destinations['thumb'], $destinations['preview']] as $path) {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $this->bucket . $path,
            ]);
        }
    }
}