<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402162046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meet (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', host_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, category VARCHAR(255) NOT NULL, max_guests INT NOT NULL, INDEX IDX_E9F6D3CE1FB8D185 (host_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meet_user (meet_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_8C87A1583BBBF66 (meet_id), INDEX IDX_8C87A158A76ED395 (user_id), PRIMARY KEY(meet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meet_user ADD CONSTRAINT FK_8C87A1583BBBF66 FOREIGN KEY (meet_id) REFERENCES meet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meet_user ADD CONSTRAINT FK_8C87A158A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE1FB8D185');
        $this->addSql('ALTER TABLE meet_user DROP FOREIGN KEY FK_8C87A1583BBBF66');
        $this->addSql('ALTER TABLE meet_user DROP FOREIGN KEY FK_8C87A158A76ED395');
        $this->addSql('DROP TABLE meet');
        $this->addSql('DROP TABLE meet_user');
    }
}
