<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402191327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE chat_user CHANGE chat_id chat_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE meet CHANGE chat_id chat_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE chat_id chat_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE chat_id chat_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet CHANGE chat_id chat_id VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE chat_user CHANGE chat_id chat_id VARCHAR(255) NOT NULL');
    }
}
