<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250502160009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add address table and update user table to use UUIDs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE address (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', street VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_user CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE meet_user CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE message CHANGE author_id author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE birthday birthday DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE meet_user CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_user CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE author_id author_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP updated_at, CHANGE id id VARCHAR(255) NOT NULL, CHANGE birthday birthday DATETIME NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
