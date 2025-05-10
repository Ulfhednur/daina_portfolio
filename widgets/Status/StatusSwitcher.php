<?php

namespace app\widgets\Status;

use yii\bootstrap5\Widget;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      RARS promocode system
 * @copyright    Copyright (C) 2025 RARS. All rights reserved.
 * @license      GNU/GPL
 */
class StatusSwitcher extends Widget
{
    public bool $status = true;

    /**
     * {@inheritdoc}
     */
    public function run(): void
    {
        echo $this->render('@app/widgets/Status/layouts/Switcher.php', ['status' => $this->status]);
    }
}
