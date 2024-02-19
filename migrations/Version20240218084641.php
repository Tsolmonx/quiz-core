<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218084641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_quiz_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_quiz_response (id INT NOT NULL, quiz_id INT NOT NULL, quiz_taker_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72C2CF12853CD175 ON app_quiz_response (quiz_id)');
        $this->addSql('CREATE INDEX IDX_72C2CF12DDEA8D86 ON app_quiz_response (quiz_taker_id)');
        $this->addSql('CREATE INDEX IDX_72C2CF121E27F6BF ON app_quiz_response (question_id)');
        $this->addSql('CREATE TABLE app_quiz_response_answers (quiz_response_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(quiz_response_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_FF61F25FD4D53BE0 ON app_quiz_response_answers (quiz_response_id)');
        $this->addSql('CREATE INDEX IDX_FF61F25FAA334807 ON app_quiz_response_answers (answer_id)');
        $this->addSql('ALTER TABLE app_quiz_response ADD CONSTRAINT FK_72C2CF12853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response ADD CONSTRAINT FK_72C2CF12DDEA8D86 FOREIGN KEY (quiz_taker_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response ADD CONSTRAINT FK_72C2CF121E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response_answers ADD CONSTRAINT FK_FF61F25FD4D53BE0 FOREIGN KEY (quiz_response_id) REFERENCES app_quiz_response (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_quiz_response_answers ADD CONSTRAINT FK_FF61F25FAA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_quiz_response_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_quiz_response DROP CONSTRAINT FK_72C2CF12853CD175');
        $this->addSql('ALTER TABLE app_quiz_response DROP CONSTRAINT FK_72C2CF12DDEA8D86');
        $this->addSql('ALTER TABLE app_quiz_response DROP CONSTRAINT FK_72C2CF121E27F6BF');
        $this->addSql('ALTER TABLE app_quiz_response_answers DROP CONSTRAINT FK_FF61F25FD4D53BE0');
        $this->addSql('ALTER TABLE app_quiz_response_answers DROP CONSTRAINT FK_FF61F25FAA334807');
        $this->addSql('DROP TABLE app_quiz_response');
        $this->addSql('DROP TABLE app_quiz_response_answers');
    }
}
