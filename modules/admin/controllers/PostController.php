<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */


namespace app\modules\admin\controllers;

use app\models\Post;

class PostController extends OrderableController
{
    /**
     * {@inheritdoc}
     */
    protected string $modelClass = Post::class;

    /**
     * {@inheritdoc}
     */
    protected string $tmpl = 'Post';
}