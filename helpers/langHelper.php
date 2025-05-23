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

/**
 * abstract class langHelper
 * Библиотека функций для работы с языком сайта
 */
abstract class langHelper
{
    /** Короткие коды языка для УРЛ */
    const string LANG_RU = 'ru';
    const string LANG_EN = 'en';

    /** Валидные коды языка для фреймворка */
    const string LANG_CODE_RU = 'ru-RU';
    const string LANG_CODE_EN = 'en-GB';

    /** Язык по-умолчанию */
    const string DEFAULT_LANG = 'ru';

    /** Соответствие валидных кодов коротким */
    public static array $langCodes = [
        self::LANG_RU => self::LANG_CODE_RU,
        self::LANG_EN => self::LANG_CODE_EN,
    ];

    /** Названия языков */
    public static array $langTitles = [
        self::LANG_RU => 'Русский',
        self::LANG_EN => 'English',
    ];

    /**
     * Валидный код текущего языка
     *
     * @return string
     */
    public static function getCurrentLangCode(): string
    {

        return self::$langCodes[Yii::$app->getRequest()->get('language') ?? self::DEFAULT_LANG];
    }

    /**
     * Короткий код текущего языка
     *
     * @return string
     */
    public static function getCurrentLang(): string
    {
        return Yii::$app->getRequest()->get('language') ?? self::DEFAULT_LANG;
    }

    /**
     * Является ли текущий язык языком по умолчанию
     *
     * @return bool
     */
    public static function isLangDefault(): bool
    {
        return self::getCurrentLang() == self::DEFAULT_LANG;
    }

    /**
     * Устанавливает язык из УРЛ текущим языком системы
     * Если языка в УРЛ нет - показывает ошибку на языке по умолчанию
     *
     * @return void
     * @throws NotFoundHttpException
     */
    public static function setCurrentLang(): void
    {
        Yii::$app->language = langHelper::getCurrentLangCode();
        if (
            !Yii::$app->getRequest()->get('language')
            || !array_key_exists(Yii::$app->getRequest()->get('language'), self::$langCodes)
        ) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Массив данных о не-текущих языках для alternate-hreflang ссылок и переключателя языка
     *
     * @param array|null $path
     *
     * @return array
     */
    public static function getLangHrefs(?array $path = []): array
    {
        $langHrefs = [];
        if (empty($path)) {
            $path = ['/'];
        }
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