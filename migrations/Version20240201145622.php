<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201145622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, created_by INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type VARCHAR(50) NOT NULL, request_status VARCHAR(50) NOT NULL, level INT NOT NULL, is_grouped BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A412FA92DE12AB56 ON quiz (created_by)');
        $this->addSql('CREATE INDEX IDX_A412FA92727ACA70 ON quiz (parent_id)');
        $this->addSql('COMMENT ON COLUMN quiz.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN quiz.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92727ACA70 FOREIGN KEY (parent_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA92DE12AB56');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA92727ACA70');
        $this->addSql('DROP TABLE quiz');
    }
}
