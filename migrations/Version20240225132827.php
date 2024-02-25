<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225132827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_question_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_question_response (id INT NOT NULL, question_id INT NOT NULL, quiz_response_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FC3616681E27F6BF ON app_question_response (question_id)');
        $this->addSql('CREATE INDEX IDX_FC361668D4D53BE0 ON app_question_response (quiz_response_id)');
        $this->addSql('CREATE TABLE app_question_response_answers (question_response_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(question_response_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_7C7F6C6D18F7F7CD ON app_question_response_answers (question_response_id)');
        $this->addSql('CREATE INDEX IDX_7C7F6C6DAA334807 ON app_question_response_answers (answer_id)');
        $this->addSql('ALTER TABLE app_question_response ADD CONSTRAINT FK_FC3616681E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response ADD CONSTRAINT FK_FC361668D4D53BE0 FOREIGN KEY (quiz_response_id) REFERENCES app_quiz_response (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response_answers ADD CONSTRAINT FK_7C7F6C6D18F7F7CD FOREIGN KEY (question_response_id) REFERENCES app_question_response (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response_answers ADD CONSTRAINT FK_7C7F6C6DAA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response_answers DROP CONSTRAINT fk_ff61f25fd4d53be0');
        $this->addSql('ALTER TABLE app_quiz_response_answers DROP CONSTRAINT fk_ff61f25faa334807');
        $this->addSql('DROP TABLE app_quiz_response_answers');
        $this->addSql('ALTER TABLE app_quiz_response DROP CONSTRAINT fk_72c2cf121e27f6bf');
        $this->addSql('DROP INDEX idx_72c2cf121e27f6bf');
        $this->addSql('ALTER TABLE app_quiz_response DROP question_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_question_response_id_seq CASCADE');
        $this->addSql('CREATE TABLE app_quiz_response_answers (quiz_response_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(quiz_response_id, answer_id))');
        $this->addSql('CREATE INDEX idx_ff61f25faa334807 ON app_quiz_response_answers (answer_id)');
        $this->addSql('CREATE INDEX idx_ff61f25fd4d53be0 ON app_quiz_response_answers (quiz_response_id)');
        $this->addSql('ALTER TABLE app_quiz_response_answers ADD CONSTRAINT fk_ff61f25fd4d53be0 FOREIGN KEY (quiz_response_id) REFERENCES app_quiz_response (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response_answers ADD CONSTRAINT fk_ff61f25faa334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response DROP CONSTRAINT FK_FC3616681E27F6BF');
        $this->addSql('ALTER TABLE app_question_response DROP CONSTRAINT FK_FC361668D4D53BE0');
        $this->addSql('ALTER TABLE app_question_response_answers DROP CONSTRAINT FK_7C7F6C6D18F7F7CD');
        $this->addSql('ALTER TABLE app_question_response_answers DROP CONSTRAINT FK_7C7F6C6DAA334807');
        $this->addSql('DROP TABLE app_question_response');
        $this->addSql('DROP TABLE app_question_response_answers');
        $this->addSql('ALTER TABLE app_quiz_response ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE app_quiz_response ADD CONSTRAINT fk_72c2cf121e27f6bf FOREIGN KEY (question_id) REFERENCES app_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_72c2cf121e27f6bf ON app_quiz_response (question_id)');
    }
}
