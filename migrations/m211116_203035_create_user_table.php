<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m211116_203035_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     * @noinspection SqlNoDataSourceInspection
     */
    public function safeUp(): bool
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `user` (
                `id` bigint COMMENT 'Unique identifier for this user or bot',
                `first_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User''s or bot''s first name',
                `last_name` CHAR(255) DEFAULT NULL COMMENT 'User''s or bot''s last name',
                `username` CHAR(191) DEFAULT NULL COMMENT 'User''s or bot''s username',
                `last_request` VARCHAR(255),
                `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
                `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
                PRIMARY KEY (`id`),
                KEY `username` (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    	");

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool
    {
        echo "m211116_203035_create_user_table cannot be reverted.\n";

        return false;
    }
}
