<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213152532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_question ADD quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE app_question ADD CONSTRAINT FK_BE7729E3853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BE7729E3853CD175 ON app_question (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_question DROP CONSTRAINT FK_BE7729E3853CD175');
        $this->addSql('DROP INDEX IDX_BE7729E3853CD175');
        $this->addSql('ALTER TABLE app_question DROP quiz_id');
    }
}
