<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225134541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_question_response_right_answers (question_response_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(question_response_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_4BF6BA5918F7F7CD ON app_question_response_right_answers (question_response_id)');
        $this->addSql('CREATE INDEX IDX_4BF6BA59AA334807 ON app_question_response_right_answers (answer_id)');
        $this->addSql('ALTER TABLE app_question_response_right_answers ADD CONSTRAINT FK_4BF6BA5918F7F7CD FOREIGN KEY (question_response_id) REFERENCES app_question_response (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response_right_answers ADD CONSTRAINT FK_4BF6BA59AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_question_response ADD is_correct BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE app_quiz_response ADD total_right_answers INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_quiz_response ADD percent DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE app_question_response_right_answers DROP CONSTRAINT FK_4BF6BA5918F7F7CD');
        $this->addSql('ALTER TABLE app_question_response_right_answers DROP CONSTRAINT FK_4BF6BA59AA334807');
        $this->addSql('DROP TABLE app_question_response_right_answers');
        $this->addSql('ALTER TABLE app_question_response DROP is_correct');
        $this->addSql('ALTER TABLE app_quiz_response DROP total_right_answers');
        $this->addSql('ALTER TABLE app_quiz_response DROP percent');
    }
}
