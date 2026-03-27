<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260326000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace Meet→Host→User indirection with direct Meet→User FK (host_user_id)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meet ADD host_user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE meet m INNER JOIN host h ON m.host_id = h.id SET m.host_user_id = h.user_id');
        $this->addSql('ALTER TABLE meet MODIFY host_user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE1FB8D185');
        $this->addSql('ALTER TABLE meet DROP INDEX UNIQ_E9F6D3CE1FB8D185');
        $this->addSql('ALTER TABLE meet DROP COLUMN host_id');
        $this->addSql('ALTER TABLE host DROP FOREIGN KEY FK_CF2713FDA76ED395');
        $this->addSql('DROP TABLE host');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_meet_host_user FOREIGN KEY (host_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_meet_host_user ON meet (host_user_id)');
    }

    public function down(Schema $schema): void
    {
    }
}
