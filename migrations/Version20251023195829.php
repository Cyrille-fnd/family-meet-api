<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251023195829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Meet host and guests structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE guest (id VARCHAR(255) NOT NULL, meet_id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, INDEX IDX_ACB79A353BBBF66 (meet_id), INDEX IDX_ACB79A35A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE host (id VARCHAR(255) NOT NULL, meet_id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CF2713FD3BBBF66 (meet_id), INDEX IDX_CF2713FDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A353BBBF66 FOREIGN KEY (meet_id) REFERENCES meet (id)');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE host ADD CONSTRAINT FK_CF2713FD3BBBF66 FOREIGN KEY (meet_id) REFERENCES meet (id)');
        $this->addSql('ALTER TABLE host ADD CONSTRAINT FK_CF2713FDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meet ADD UNIQUE INDEX UNIQ_E9F6D3CE1FB8D185 (host_id)');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id VARCHAR(255) DEFAULT NULL, CHANGE date date DATETIME NOT NULL COMMENT \'(DC2Type:extended_datetime_immutable)\', CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:extended_datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:extended_datetime_immutable)\'');
        $this->addSql('ALTER TABLE meet ADD CONSTRAINT FK_E9F6D3CE1FB8D185 FOREIGN KEY (host_id) REFERENCES host (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meet DROP FOREIGN KEY FK_E9F6D3CE1FB8D185');
        $this->addSql('CREATE TABLE meet_user (meet_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, user_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8C87A158A76ED395 (user_id), INDEX IDX_8C87A1583BBBF66 (meet_id), PRIMARY KEY(meet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE meet_user ADD CONSTRAINT FK_8C87A1583BBBF66 FOREIGN KEY (meet_id) REFERENCES meet (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A353BBBF66');
        $this->addSql('ALTER TABLE guest DROP FOREIGN KEY FK_ACB79A35A76ED395');
        $this->addSql('ALTER TABLE host DROP FOREIGN KEY FK_CF2713FD3BBBF66');
        $this->addSql('ALTER TABLE host DROP FOREIGN KEY FK_CF2713FDA76ED395');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE host');
        $this->addSql('ALTER TABLE meet DROP INDEX UNIQ_E9F6D3CE1FB8D185, ADD INDEX IDX_E9F6D3CE1FB8D185 (host_id)');
        $this->addSql('ALTER TABLE meet CHANGE host_id host_id VARCHAR(255) NOT NULL, CHANGE date date DATE NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
