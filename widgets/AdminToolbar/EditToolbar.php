<?php
declare(strict_types=1);

namespace app\widgets\AdminToolbar;

use yii\bootstrap5\Widget;

/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

class EditToolbar extends Widget
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function run(): void
    {
        echo $this->render('@app/widgets/AdminToolbar/layouts/EditToolbar.php', ['options' => $this->options]);
    }
}
