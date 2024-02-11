<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201150735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, question_qroup_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E278851E7 ON question (question_qroup_id)');
        $this->addSql('CREATE TABLE question_group (id INT NOT NULL, quiz_id INT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D2B55C1853CD175 ON question_group (quiz_id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E278851E7 FOREIGN KEY (question_qroup_id) REFERENCES question_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question_group ADD CONSTRAINT FK_5D2B55C1853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_group_id_seq CASCADE');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E278851E7');
        $this->addSql('ALTER TABLE question_group DROP CONSTRAINT FK_5D2B55C1853CD175');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_group');
    }
}
