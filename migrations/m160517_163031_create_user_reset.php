<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_reset`.
 */
class m160517_163031_create_user_reset extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_reset', [
            'id' => $this->primaryKey(),

            'user_id' => $this->integer()->notNull(),
            'code' => $this->string()->notNull()->unique(),
            'expires' => $this->integer()->notNull(),
            'used' => $this->integer(1)->notNull()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('user_reset_user_id_fk', 'user_reset', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_reset');
    }
}
