<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211132311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_answer ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE app_answer ADD CONSTRAINT FK_3FDE27A51E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3FDE27A51E27F6BF ON app_answer (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_answer DROP CONSTRAINT FK_3FDE27A51E27F6BF');
        $this->addSql('DROP INDEX IDX_3FDE27A51E27F6BF');
        $this->addSql('ALTER TABLE app_answer DROP question_id');
    }
}
