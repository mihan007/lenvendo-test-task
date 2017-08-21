<?php

use yii\db\Migration;
use yii\db\pgsql\Schema;

class m161030_153616_init_db extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('images', [
            'image_id' => Schema::TYPE_PK,
            'password' => Schema::TYPE_STRING . ' NOT NULL',
            'salt' => Schema::TYPE_STRING . ' NOT NULL',
            'body' => Schema::TYPE_BINARY,
            'created' => Schema::TYPE_INTEGER,
            'updated' => Schema::TYPE_INTEGER,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('images');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
