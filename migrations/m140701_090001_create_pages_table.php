<?php

use yii\db\Schema;

class m140701_090001_create_pages_table extends \yii\db\Migration
{
    public function up()
    {
        switch (Yii::$app->db->driverName) {
            case 'mysql':
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
                break;
            case 'pgsql':
                $tableOptions = null;
                break;
            default:
                throw new RuntimeException('Your database is not supported!');
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
        $this->addForeignKey('fk_pages_pages_lang', '{{%pages_lang}}', 'page_id', '{{%pages}}', 'id', 'CASCADE', 'DELETE');
        
    }

    public function down()
    {
        $this->dropTable('pages');
        $this->dropTable('pages_lang');
    }
}
