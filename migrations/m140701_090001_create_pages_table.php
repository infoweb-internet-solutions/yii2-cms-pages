<?php

use yii\db\Schema;

class m140701_090001_create_pages_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Create 'pages' table
        $this->createTable('{{%pages}}', [
            'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
            'template' => 'INT(10) UNSIGNED NOT NULL',
            'active' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at' => 'INT(10) UNSIGNED NOT NULL',
            'updated_at' => 'INT(10) UNSIGNED NOT NULL',
            0 => 'PRIMARY KEY (`id`)'
        ], $tableOptions);
        
        // Create 'pages_lang' table
        $this->createTable('{{%pages_lang}}', [
            'page_id' => 'INT(10) UNSIGNED NOT NULL',
            'language' => 'VARCHAR(2) NOT NULL',
            'slug' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'content' => 'TEXT NOT NULL',
            'created_at' => 'INT(10) UNSIGNED NOT NULL',
            'updated_at' => 'INT(10) UNSIGNED NOT NULL',
            0 => 'PRIMARY KEY (`page_id`)',
            0 => 'PRIMARY KEY (`language`)'
        ], $tableOptions);
        $this->addForeignKey('FK_PAGES_LANG_PAGE_ID', '{{%pages_lang}}', 'page_id', '{{%pages}}', 'id', 'CASCADE', 'RESTRICT');
        
    }

    public function down()
    {
        $this->dropTable('pages_lang');
        $this->dropTable('pages');        
    }
}
