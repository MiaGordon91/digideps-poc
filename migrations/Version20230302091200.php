<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302091200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649f90dba0c');
        $this->addSql('DROP INDEX uniq_8d93d64999463b16');
        $this->addSql('DROP INDEX uniq_8d93d6491503adbb');
        $this->addSql('DROP INDEX uniq_8d93d649f9fba823');
        $this->addSql('DROP INDEX uniq_8d93d6498039fbb8');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649f90dba0c ON "user" (clients_case_number)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64999463b16 ON "user" (clients_last_name)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6491503adbb ON "user" (clients_first_names)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649f9fba823 ON "user" (deputy_last_name)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6498039fbb8 ON "user" (deputy_first_name)');
    }
}
