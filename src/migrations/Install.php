<?php

namespace dowleydeveloped\passwordprotection\migrations;

use Craft;
use dowleydeveloped\passwordprotection\records\LoginAttempt;
use dowleydeveloped\passwordprotection\records\PasswordRecord;
use craft\db\Migration;
use Yii;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $passwordTable = Yii::$app->db->schema->getTableSchema(PasswordRecord::tableName());
        $loginTable = Yii::$app->db->schema->getTableSchema(LoginAttempt::tableName());

        // Entry Passwords
        if (!$passwordTable)
        {
            $this->createTable(PasswordRecord::tableName(), [
                'id' => $this->primaryKey(),
                'elementId' => $this->integer()->notNull(),
                'password' => $this->string(),
            ]);
        }

        // Login Attempts
        if (!$loginTable)
        {
            $this->createTable(LoginAttempt::tableName(), [
                'id' => $this->primaryKey(),
                'key' => $this->string()->notNull(),
                'ipAddress' => $this->string(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);


            $this->createIndex(null, LoginAttempt::tableName(), 'key');
            $this->createIndex(null, LoginAttempt::tableName(), 'ipAddress');
        }

		$this->createTable('{{%dowley_password_content}}', [
			'id' => $this->primaryKey(),
			'heading' => $this->text(),
			'content' => $this->text(),
			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
			'uid' => $this->uid(),
		]);

		$this->insert('{{%dowley_password_content}}', [
			'heading' => 'Protected Page',
			'content' => "",
			'dateCreated' => new \yii\db\Expression('NOW()'),
			'dateUpdated' => new \yii\db\Expression('NOW()'),
			'uid' => \craft\helpers\StringHelper::UUID(),
		]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $passwordTable = Yii::$app->db->schema->getTableSchema(PasswordRecord::tableName());
        $loginTable = Yii::$app->db->schema->getTableSchema(LoginAttempt::tableName());

        // Truncate & Drop Password Table
        if ($passwordTable)
        {
            $this->truncateTable(PasswordRecord::tableName());
            $this->dropTable(PasswordRecord::tableName());
        };

        // Truncate & Drop Login Table
        if ($loginTable)
        {
            $this->truncateTable(LoginAttempt::tableName());
            $this->dropTable(LoginAttempt::tableName());
        };

        return true;
    }
}
