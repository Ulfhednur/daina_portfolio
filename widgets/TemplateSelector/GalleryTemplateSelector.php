<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\widgets\TemplateSelector;

use yii\widgets\InputWidget;

/**
 * Выбор шаблона галереи
 */
class GalleryTemplateSelector extends InputWidget
{
    const string TMPL_MASONRY = 'masonry';

    const string TMPL_JUSTIFIED = 'justified';

    const string TMPL_GRID = 'grid';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo $this->render('@app/widgets/TemplateSelector/layouts/galleryTmplSelector.php', [
            'value' => $this->value,
            'name' => $this->name,
        ]);
    }

    public static function getTypes(): array
    {
        return [
            self::TMPL_MASONRY,
            self::TMPL_JUSTIFIED,
            self::TMPL_GRID,
        ];
    }

    public static function getTypeNames(): array
    {
        return [
            self::TMPL_MASONRY => 'Мозаика (верт)',
            self::TMPL_JUSTIFIED => 'Мозаика (гор)',
            self::TMPL_GRID => 'Сетка',
        ];
    }
}