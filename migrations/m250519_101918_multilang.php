<?php

use yii\db\Migration;

class m250519_101918_multilang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('items', 'title_en', $this->string(255)->notNull()->defaultValue('')->after('title'));
        $this->addColumn('items', 'subtitle_en', $this->string(255)->notNull()->defaultValue('')->after('subtitle'));
        $this->addColumn('items', 'description_en', $this->text()->notNull()->defaultValue('')->after('description'));
        $this->addColumn('items', 'seo_title_en', $this->string(1023)->notNull()->defaultValue('')->after('seo_title'));
        $this->addColumn('items', 'seo_description_en', $this->text()->notNull()->defaultValue('')->after('seo_description'));

        $this->addColumn('media', 'title_en', $this->string(255)->notNull()->defaultValue('')->after('title'));
        $this->addColumn('media', 'alt_en', $this->string(64)->notNull()->defaultValue('')->after('alt'));
        $this->addColumn('media', 'description_en', $this->string(64)->notNull()->defaultValue('')->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('items', 'title_en');
        $this->dropColumn('items', 'subtitle_en');
        $this->dropColumn('items', 'description_en');
        $this->dropColumn('items', 'seo_title_en');
        $this->dropColumn('items', 'seo_description_en');
        $this->dropColumn('media', 'title_en');
        $this->dropColumn('media', 'alt_en');
        $this->dropColumn('media', 'description_en');
    }

}
