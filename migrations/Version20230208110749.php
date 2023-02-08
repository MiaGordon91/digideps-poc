<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208110749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE money_out ADD category_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE money_out ADD CONSTRAINT FK_E05940DA294CCED FOREIGN KEY (category_type_id) REFERENCES money_out_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E05940DA294CCED ON money_out (category_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE money_out DROP CONSTRAINT FK_E05940DA294CCED');
        $this->addSql('DROP INDEX IDX_E05940DA294CCED');
        $this->addSql('ALTER TABLE money_out DROP category_type_id');
    }
}
