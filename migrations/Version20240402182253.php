<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402182253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet ADD chat_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E9F6D3CE1A9A7125 ON meet (chat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE1A9A7125');
        $this->addSql('DROP INDEX UNIQ_E9F6D3CE1A9A7125 ON meet');
        $this->addSql('ALTER TABLE meet DROP chat_id');
    }
}
