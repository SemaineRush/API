<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122151037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE candidate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE election_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character (id INT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB03492FC23A8 ON character (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB034A0D96FBF ON character (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB034C05FB297 ON character (confirmation_token)');
        $this->addSql('COMMENT ON COLUMN character.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE candidate (id INT NOT NULL, user_related_id INT DEFAULT NULL, stylesheet TEXT NOT NULL, infos TEXT NOT NULL, nb_votes INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C8B28E44E60506ED ON candidate (user_related_id)');
        $this->addSql('CREATE TABLE election (id INT NOT NULL, start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, "end" TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE election_candidate (election_id INT NOT NULL, candidate_id INT NOT NULL, PRIMARY KEY(election_id, candidate_id))');
        $this->addSql('CREATE INDEX IDX_D6E9C32EA708DAFF ON election_candidate (election_id)');
        $this->addSql('CREATE INDEX IDX_D6E9C32E91BD8781 ON election_candidate (candidate_id)');
        $this->addSql('CREATE TABLE election_user (election_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(election_id, user_id))');
        $this->addSql('CREATE INDEX IDX_11DCA16FA708DAFF ON election_user (election_id)');
        $this->addSql('CREATE INDEX IDX_11DCA16FA76ED395 ON election_user (user_id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44E60506ED FOREIGN KEY (user_related_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE election_candidate ADD CONSTRAINT FK_D6E9C32EA708DAFF FOREIGN KEY (election_id) REFERENCES election (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE election_candidate ADD CONSTRAINT FK_D6E9C32E91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE election_user ADD CONSTRAINT FK_11DCA16FA708DAFF FOREIGN KEY (election_id) REFERENCES election (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE election_user ADD CONSTRAINT FK_11DCA16FA76ED395 FOREIGN KEY (user_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candidate DROP CONSTRAINT FK_C8B28E44E60506ED');
        $this->addSql('ALTER TABLE election_user DROP CONSTRAINT FK_11DCA16FA76ED395');
        $this->addSql('ALTER TABLE election_candidate DROP CONSTRAINT FK_D6E9C32E91BD8781');
        $this->addSql('ALTER TABLE election_candidate DROP CONSTRAINT FK_D6E9C32EA708DAFF');
        $this->addSql('ALTER TABLE election_user DROP CONSTRAINT FK_11DCA16FA708DAFF');
        $this->addSql('DROP SEQUENCE character_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE candidate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE election_id_seq CASCADE');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE election');
        $this->addSql('DROP TABLE election_candidate');
        $this->addSql('DROP TABLE election_user');
    }
}
