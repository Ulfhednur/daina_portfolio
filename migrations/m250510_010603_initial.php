<?php
declare(strict_types=1);
/**
 * @version      1.0
 * @author       Tempadmin
 * @package      Daina portfolio
 * @copyright    Copyright (C) 2025 Daina. All rights reserved.
 * @license      GNU/GPL
 */

use yii\db\Migration;

class m250510_010603_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('items', [
            'id' => $this->primaryKey()->unsigned(),
            'image_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'published' => $this->boolean()->notNull()->defaultValue(true),
            'alias' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'subtitle' => $this->string(255),
            'description' => $this->text(),
            'seo_title' => $this->string(1023),
            'seo_description' => $this->text(),
            'ordering' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'created_date' => $this->datetime()->notNull(),
        ]);

        $this->execute("ALTER TABLE items ADD item_type ENUM('page', 'post', 'gallery') AFTER id");
        $this->createIndex('type_items', 'items', ['item_type', 'published']);
        $this->createIndex('items_alias', 'items', 'alias');

        $this->createTable('media_folders', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'title' => $this->string(255)->notNull()
        ]);

        $this->createIndex('media_folders_unique_name', 'media_folders', ['parent_id', 'title'], true);

        $this->createTable('media', [
            'id' => $this->primaryKey()->unsigned(),
            'folder_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'title' => $this->string(255)->notNull()->defaultValue(''),
            'alt' => $this->string(64)->notNull()->defaultValue(''),
            'description' => $this->string(64)->notNull()->defaultValue(''),
            'path' => $this->string(1024)->notNull(),
            'thumbnail' => $this->string(1024)->notNull(),
            'preview' => $this->string(1024)->notNull(),
            'url' => $this->string(1024)->notNull()->defaultValue(''),
            'url_thumbnail' => $this->string(1024)->notNull()->defaultValue(''),
            'url_preview' => $this->string(1024)->notNull()->defaultValue(''),
        ]);

        $this->createIndex('media_folder', 'media', 'folder_id');
        $this->createIndex('media_path', 'media', 'path', true);

        $this->createTable('media_gallery', [
            'item_id' => $this->integer(11)->unsigned()->notNull(),
            'media_id' => $this->integer(11)->unsigned()->notNull(),
            'ordering' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->addPrimaryKey('media_gallery_pk', 'media_gallery', ['item_id', 'media_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('media_gallery');
        $this->dropTable('media');
        $this->dropTable('media_folders');
        $this->dropTable('items');
    }
}
