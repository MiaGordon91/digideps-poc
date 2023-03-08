<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301143845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD deputy_first_name VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD deputy_last_name VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD clients_first_names VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD clients_last_name VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD clients_case_number VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498039FBB8 ON "user" (deputy_first_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F9FBA823 ON "user" (deputy_last_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491503ADBB ON "user" (clients_first_names)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64999463B16 ON "user" (clients_last_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F90DBA0C ON "user" (clients_case_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_8D93D6498039FBB8');
        $this->addSql('DROP INDEX UNIQ_8D93D649F9FBA823');
        $this->addSql('DROP INDEX UNIQ_8D93D6491503ADBB');
        $this->addSql('DROP INDEX UNIQ_8D93D64999463B16');
        $this->addSql('DROP INDEX UNIQ_8D93D649F90DBA0C');
        $this->addSql('ALTER TABLE "user" DROP deputy_first_name');
        $this->addSql('ALTER TABLE "user" DROP deputy_last_name');
        $this->addSql('ALTER TABLE "user" DROP clients_first_names');
        $this->addSql('ALTER TABLE "user" DROP clients_last_name');
        $this->addSql('ALTER TABLE "user" DROP clients_case_number');
    }
}
