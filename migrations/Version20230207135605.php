<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207135605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE money_out DROP CONSTRAINT fk_e05940da9d86650f');
        $this->addSql('DROP INDEX idx_e05940da9d86650f');
        $this->addSql('ALTER TABLE money_out RENAME COLUMN user_id_id TO deputy_user_id');
        $this->addSql('ALTER TABLE money_out ADD CONSTRAINT FK_E05940DAE98FD210 FOREIGN KEY (deputy_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E05940DAE98FD210 ON money_out (deputy_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE money_out DROP CONSTRAINT FK_E05940DAE98FD210');
        $this->addSql('DROP INDEX IDX_E05940DAE98FD210');
        $this->addSql('ALTER TABLE money_out RENAME COLUMN deputy_user_id TO user_id_id');
        $this->addSql('ALTER TABLE money_out ADD CONSTRAINT fk_e05940da9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e05940da9d86650f ON money_out (user_id_id)');
    }
}
