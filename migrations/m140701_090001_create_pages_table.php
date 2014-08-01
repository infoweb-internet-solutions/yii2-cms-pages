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

        $this->createTable('pages', [
            'id'            => Schema::TYPE_PK,
            'title'         => Schema::TYPE_STRING . '(255) NOT NULL',
            'content'       => Schema::TYPE_TEXT . ' NOT NULL',
            'active'        => Schema::TYPE_SMALLINT . "(3) UNSIGNED NOT NULL DEFAULT '1'",
            'created_at'    => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'  => Schema::TYPE_TIMESTAMP . ' NOT NULL'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('pages');
    }
}
