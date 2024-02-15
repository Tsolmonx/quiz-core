<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240215065249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_answer_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_question_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_quiz_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_user_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_answer_image (id INT NOT NULL, owner_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C89F78D7E3C61F9 ON app_answer_image (owner_id)');
        $this->addSql('CREATE TABLE app_question_image (id INT NOT NULL, owner_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2A2C87A57E3C61F9 ON app_question_image (owner_id)');
        $this->addSql('CREATE TABLE app_quiz_image (id INT NOT NULL, owner_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6F55D6A37E3C61F9 ON app_quiz_image (owner_id)');
        $this->addSql('CREATE TABLE app_user_image (id INT NOT NULL, owner_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, type VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71E4547B7E3C61F9 ON app_user_image (owner_id)');
        $this->addSql('ALTER TABLE app_answer_image ADD CONSTRAINT FK_9C89F78D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_image ADD CONSTRAINT FK_2A2C87A57E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_image ADD CONSTRAINT FK_6F55D6A37E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_user_image ADD CONSTRAINT FK_71E4547B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_answer_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_question_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_quiz_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_user_image_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_answer_image DROP CONSTRAINT FK_9C89F78D7E3C61F9');
        $this->addSql('ALTER TABLE app_question_image DROP CONSTRAINT FK_2A2C87A57E3C61F9');
        $this->addSql('ALTER TABLE app_quiz_image DROP CONSTRAINT FK_6F55D6A37E3C61F9');
        $this->addSql('ALTER TABLE app_user_image DROP CONSTRAINT FK_71E4547B7E3C61F9');
        $this->addSql('DROP TABLE app_answer_image');
        $this->addSql('DROP TABLE app_question_image');
        $this->addSql('DROP TABLE app_quiz_image');
        $this->addSql('DROP TABLE app_user_image');
    }
}
