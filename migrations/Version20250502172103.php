<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250502172103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change UUID columns to VARCHAR(255)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE chat_user CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet_user CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE author_id author_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE chat_user CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE message CHANGE author_id author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE meet_user CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
