<?php
declare(strict_types=1);

namespace app\widgets\ImageSelector;

use app\assets\FileManager\FileManagerAsset;
use app\models\Folder;
use app\models\Media;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

class ImageSelector extends InputWidget
{
    const string SELECT_SINGLE = 'single';

    const string SELECT_MULTIPLE = 'multiple';

    const string SELECT_NONE = 'none';

    public string $selectType = self::SELECT_MULTIPLE;

    public string $buttonText = 'Медиа менеджер';

    public string $buttonClass = 'uk-button uk-button-primary';

    public array $selectedMedia = [];

    public int $galleryId = 0;

    public static bool $rendered = false;

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function run(): void
    {
        switch ($this->selectType) {
            case self::SELECT_SINGLE:
                $ImageSelectorMultiselect = 0;
                $js = 'let ImageSelectorMultiselect = 0;';
                echo Html::beginTag('div', ['id' => 'image-selector-fields']);
                if ($this->hasModel()) {
                    echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
                } else {
                    echo Html::hiddenInput($this->name, $this->value, $this->options);
                }
                echo Html::endTag('div');
                break;

            case self::SELECT_MULTIPLE:
                $ImageSelectorMultiselect = 1;
                $js = 'let ImageSelectorMultiselect = 1;';
                echo Html::beginTag('div', ['id' => 'image-selector-fields']);
                foreach ($this->selectedMedia as $mediaId) {
                    if ($this->hasModel()) {
                        echo Html::activeHiddenInput($this->model, $this->attribute);
                    } else {
                        echo Html::hiddenInput($this->name, $mediaId);
                    }
                }
                echo Html::endTag('div');
                break;

            case self::SELECT_NONE:
            default:
                $ImageSelectorMultiselect = -1;
                $js = 'let ImageSelectorMultiselect = -1;';
                break;
        }

        if (!self::$rendered) {
            $view = $this->getView();
            FileManagerAsset::register($view);
            $view->registerJs($js, $view::POS_END);
            $view->registerJs('let ImageSelectorInputName = "' . ($this->attribute ?? $this->name) . '"', $view::POS_END);
            $view->registerJs('let galleryId = ' . $this->galleryId . ';', $view::POS_END);
        }

        echo $this->render('@app/widgets/ImageSelector/layouts/ImageSelector.php', [
            'buttonText' => $this->buttonText,
            'ImageSelectorMultiselect' => $ImageSelectorMultiselect,
            'ImageSelectorInputName' => $this->attribute ?? $this->name,
            'galleryId' => $this->galleryId,
            'buttonClass' => $this->buttonClass,
        ]);

        if ($this->selectType == self::SELECT_SINGLE) {
            echo Html::beginTag('div', ['id' => 'selected-image-preview']);
            if ($this->hasModel() && $this->model->image) {
                echo Html::img($this->model->image->url_thumbnail);
            }
            echo Html::endTag('div');
        }
        self::$rendered = true;
    }
}
