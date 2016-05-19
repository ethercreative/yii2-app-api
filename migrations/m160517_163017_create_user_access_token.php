<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_access_token`.
 */
class m160517_163017_create_user_access_token extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('user_access_token', [
			'id' => $this->primaryKey(),

			'user_id' => $this->integer()->notNull(),
			'refresh_id' => $this->integer()->notNull(),
			'token' => $this->string()->notNull()->unique(),
			'expires' => $this->integer()->notNull(),

			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);

		$this->addForeignKey('user_access_token_user_id_fk', 'user_access_token', 'user_id', 'user', 'id', 'CASCADE');
		$this->addForeignKey('user_access_token_refresh_id_fk', 'user_access_token', 'refresh_id', 'user_refresh_token', 'id', 'CASCADE');
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('user_access_token');
	}
}
