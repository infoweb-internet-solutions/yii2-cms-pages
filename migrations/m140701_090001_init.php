<?php

use yii\db\Schema;

class m140701_090001_init extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Create 'pages' table
        $this->createTable('{{%pages}}', [
            'id'            => Schema::TYPE_PK,
            'type'          => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'template_id'   => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'homepage'      => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'',
            'active'        => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'position'      => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        $this->createIndex('template_id', '{{%pages}}', 'template_id');
        
        // Create 'pages_lang' table
        $this->createTable('{{%pages_lang}}', [
            'page_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
            'language'      => Schema::TYPE_STRING . '(2) NOT NULL',
            'name'          => Schema::TYPE_STRING . '(255) NOT NULL',
            'title'         => Schema::TYPE_STRING . '(255) NOT NULL',
            'content'       => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        $this->addPrimaryKey('page_id_language', '{{%pages_lang}}', ['page_id', 'language']);
        $this->createIndex('language', '{{%pages_lang}}', 'language');
        $this->addForeignKey('FK_PAGES_LANG_PAGE_ID', '{{%pages_lang}}', 'page_id', '{{%pages}}', 'id', 'CASCADE', 'RESTRICT');
        
        // Create 'page_templates' table
        $this->createTable('{{%page_templates}}', [
            'id'                    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . "(255) NOT NULL",
            'layout_model'          => Schema::TYPE_STRING . "(255) NOT NULL",
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        // Insert the default template
        $this->insert('{{%page_templates}}', [
            'name'          => 'Default',
            'layout_model'  => 'Main',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
    }

    public function down()
    {
        $this->dropTable('page_templates');
        $this->dropTable('pages_lang');
        $this->dropTable('pages');        
    }
}
