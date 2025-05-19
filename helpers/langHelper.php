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

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

abstract class langHelper
{

    const string LANG_RU = 'ru';
    const string LANG_EN = 'en';

    const string LANG_CODE_RU = 'ru-RU';
    const string LANG_CODE_EN = 'en-GB';

    const string DEFAULT_LANG = 'ru';

    public static array $langCodes = [
        self::LANG_RU => self::LANG_CODE_RU,
        self::LANG_EN => self::LANG_CODE_EN,
    ];

    public static array $langTitles = [
        self::LANG_RU => 'Русский',
        self::LANG_EN => 'English',
    ];

    public static function getCurrentLangCode(): string
    {
        return self::$langCodes[Yii::$app->getRequest()->get('language') ?? self::DEFAULT_LANG];
    }

    public static function getCurrentLang(): string
    {
        return Yii::$app->getRequest()->get('language') ?? self::DEFAULT_LANG;
    }

    public static function isLangDefault(): bool
    {
        return self::getCurrentLang() == self::DEFAULT_LANG;
    }

    public static function setCurrentLang(): void
    {
        if (!Yii::$app->getRequest()->get('language')) {
            throw new NotFoundHttpException();
        }
        Yii::$app->language = langHelper::getCurrentLangCode();
    }

    public static function getLangHrefs(array $path): array
    {
        $langHrefs = [];
        foreach (self::$langCodes as $langCode => $langName) {
            if ($langCode != self::getCurrentLang()) {
                $path['language'] = $langCode;
                $langHrefs[$langCode] = [
                    'href' => Url::to($path, 'https'),
                    'hreflang' => $langCode,
                    'langTitle' => self::$langTitles[$langCode],
                ];
            }
        }

        return $langHrefs;
    }
}