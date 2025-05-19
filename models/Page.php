<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\models;

class Page extends Item
{
    protected static string $itemType = parent::ITEM_TYPE_PAGE;
}