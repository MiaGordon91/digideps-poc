<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207135052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE money_out_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE money_out (id INT NOT NULL, user_id_id INT NOT NULL, bank_account_type VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, amount INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E05940DA9D86650F ON money_out (user_id_id)');
        $this->addSql('ALTER TABLE money_out ADD CONSTRAINT FK_E05940DA9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE money_out_id_seq CASCADE');
        $this->addSql('ALTER TABLE money_out DROP CONSTRAINT FK_E05940DA9D86650F');
        $this->addSql('DROP TABLE money_out');
    }
}
