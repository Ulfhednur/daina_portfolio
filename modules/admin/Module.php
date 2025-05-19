<?php
declare(strict_types=1);

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */
namespace app\modules\admin;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public string $basePath = '@app/modules/admin/';

    /** @var null|string $layout */
    public $layout = '@app/modules/admin/views/layouts/main';

    public function __construct($id, $parent = null, $config = [])
    {
        parent::__construct($id, $parent, $config);
        \Yii::setAlias('@admin', '@app/modules/admin');
        \Yii::setAlias('@adminViews', '@app/modules/admin/views');
    }
}