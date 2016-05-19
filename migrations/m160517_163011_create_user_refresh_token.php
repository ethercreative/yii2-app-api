<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_refresh_token`.
 */
class m160517_163011_create_user_refresh_token extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('user_refresh_token', [
			'id' => $this->primaryKey(),

			'user_id' => $this->integer()->notNull(),
			'token' => $this->string()->notNull()->unique(),
			'expires' => $this->integer()->notNull(),

			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);

		$this->addForeignKey('user_refresh_token_user_id_fk', 'user_refresh_token', 'user_id', 'user', 'id', 'CASCADE');
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('user_refresh_token');
	}
}
