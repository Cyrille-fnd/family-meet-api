<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260103173618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'remove meet_id from host table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE host DROP FOREIGN KEY FK_CF2713FD3BBBF66');
        $this->addSql('DROP INDEX UNIQ_CF2713FD3BBBF66 ON host');
        $this->addSql('ALTER TABLE host DROP meet_id');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:extended_datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE host ADD meet_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE host ADD CONSTRAINT FK_CF2713FD3BBBF66 FOREIGN KEY (meet_id) REFERENCES meet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CF2713FD3BBBF66 ON host (meet_id)');
    }
}
