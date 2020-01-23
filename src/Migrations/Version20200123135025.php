<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123135025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE score_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE score (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE candidate ADD score_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate DROP score');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4412EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C8B28E4412EB0A51 ON candidate (score_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candidate DROP CONSTRAINT FK_C8B28E4412EB0A51');
        $this->addSql('DROP SEQUENCE score_id_seq CASCADE');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP INDEX IDX_C8B28E4412EB0A51');
        $this->addSql('ALTER TABLE candidate ADD score INT NOT NULL');
        $this->addSql('ALTER TABLE candidate DROP score_id');
    }
}
