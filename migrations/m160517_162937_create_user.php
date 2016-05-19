<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160517_162937_create_user extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('user', [
			'id' => $this->primaryKey(),

			'email' => $this->string()->notNull()->unique(),
			'password' => $this->string()->notNull(),
			'display_name' => $this->string(24)->notNull(),
			'auth_key' => $this->string(32)->notNull(),

			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('user');
	}
}
