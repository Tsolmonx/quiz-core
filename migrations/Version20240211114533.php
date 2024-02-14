<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211114533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_question_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_answer (id INT NOT NULL, value VARCHAR(255) NOT NULL, is_right_answer BOOLEAN NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_question (id INT NOT NULL, question_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BE7729E3278851E7 ON app_question (question_group_id)');
        $this->addSql('CREATE TABLE app_question_group (id INT NOT NULL, quiz_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_82D1C73F853CD175 ON app_question_group (quiz_id)');
        $this->addSql('CREATE TABLE app_quiz (id INT NOT NULL, created_by INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type VARCHAR(50) NOT NULL, request_status VARCHAR(50) NOT NULL, level INT NOT NULL, is_grouped BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A13CDF32DE12AB56 ON app_quiz (created_by)');
        $this->addSql('CREATE INDEX IDX_A13CDF32727ACA70 ON app_quiz (parent_id)');
        $this->addSql('COMMENT ON COLUMN app_quiz.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN app_quiz.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_user (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88BDF3E9E7927C74 ON app_user (email)');
        $this->addSql('ALTER TABLE app_question ADD CONSTRAINT FK_BE7729E3278851E7 FOREIGN KEY (question_group_id) REFERENCES app_question_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_group ADD CONSTRAINT FK_82D1C73F853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF32DE12AB56 FOREIGN KEY (created_by) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF32727ACA70 FOREIGN KEY (parent_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_question_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_quiz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_user_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_question DROP CONSTRAINT FK_BE7729E3278851E7');
        $this->addSql('ALTER TABLE app_question_group DROP CONSTRAINT FK_82D1C73F853CD175');
        $this->addSql('ALTER TABLE app_quiz DROP CONSTRAINT FK_A13CDF32DE12AB56');
        $this->addSql('ALTER TABLE app_quiz DROP CONSTRAINT FK_A13CDF32727ACA70');
        $this->addSql('DROP TABLE app_answer');
        $this->addSql('DROP TABLE app_question');
        $this->addSql('DROP TABLE app_question_group');
        $this->addSql('DROP TABLE app_quiz');
        $this->addSql('DROP TABLE app_user');
    }
}
