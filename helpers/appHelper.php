<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\helpers;

use Random\RandomException;
use Yii;
use yii\db\Exception;

abstract class appHelper
{
    /**
     * @return string
     * @throws RandomException
     */
    public static function getV4UUID(): string
    {
        return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4) );
    }

    public static function isMenuActive($view, string $menuitem): bool
    {
        return  mb_substr($view->context->route, 0, mb_strlen(env('ADMIN_URL') . '/' . $menuitem)) == env('ADMIN_URL') . '/' . $menuitem;
    }

    public static function getPermissionPrefix(object $object, string $prefix = ''): string
    {
        $reflect = new \ReflectionClass($object);
        return str_replace($prefix, '', $reflect->getShortName());
    }

    public static function getRandString(int $length = 10, string $type = 'ascii'): string
    {
        $source = match($type) {
            'full' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_~!@#$%^&*()+=-,<.>/?',
            'hex' => '0123456789ABCDEF',
            default => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'
        };
        $charactersLength = strlen($source);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $source[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @return array
     */
    public static function getGeoData(): array
    {
        if (Yii::$app->user->isGuest) {
            /** @var Geolocation $geoService */
            $geoService = Yii::$app->params['GeoLocation'];
            return call_user_func([$geoService, 'getGeoData']);
        }

        return [
            'regin' => Geolocation::REGION_ALL,
            'city' => Geolocation::REGION_ALL,
        ];
    }

    public static function format(int|float|string|bool|null $number): string
    {
        if (!is_float($number)) {
            $number = (float) $number;
        }

        return number_format($number, 0, ',', ' ');
    }
}