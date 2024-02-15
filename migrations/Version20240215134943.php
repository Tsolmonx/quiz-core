<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215134943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_quiz_taker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_quiz_taker (id INT NOT NULL, quiz_id INT NOT NULL, quiz_taker_id INT NOT NULL, is_enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, license_expire_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_18158BB853CD175 ON app_quiz_taker (quiz_id)');
        $this->addSql('CREATE INDEX IDX_18158BBDDEA8D86 ON app_quiz_taker (quiz_taker_id)');
        $this->addSql('ALTER TABLE app_quiz_taker ADD CONSTRAINT FK_18158BB853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_taker ADD CONSTRAINT FK_18158BBDDEA8D86 FOREIGN KEY (quiz_taker_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_quiz_taker_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_quiz_taker DROP CONSTRAINT FK_18158BB853CD175');
        $this->addSql('ALTER TABLE app_quiz_taker DROP CONSTRAINT FK_18158BBDDEA8D86');
        $this->addSql('DROP TABLE app_quiz_taker');
    }
}
