<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260326234038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename index';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meet RENAME INDEX idx_meet_host_user TO IDX_E9F6D3CE9092FFA4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meet RENAME INDEX idx_e9f6d3ce9092ffa4 TO IDX_meet_host_user');
    }
}
