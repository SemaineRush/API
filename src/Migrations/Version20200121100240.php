<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121100240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, election_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A708DAFF (election_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, user_related_id INT DEFAULT NULL, stylesheet LONGTEXT NOT NULL, infos LONGTEXT NOT NULL, INDEX IDX_C8B28E44E60506ED (user_related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE election (id INT AUTO_INCREMENT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, name VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE election_candidate (election_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_D6E9C32EA708DAFF (election_id), INDEX IDX_D6E9C32E91BD8781 (candidate_id), PRIMARY KEY(election_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44E60506ED FOREIGN KEY (user_related_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE election_candidate ADD CONSTRAINT FK_D6E9C32EA708DAFF FOREIGN KEY (election_id) REFERENCES election (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE election_candidate ADD CONSTRAINT FK_D6E9C32E91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44E60506ED');
        $this->addSql('ALTER TABLE election_candidate DROP FOREIGN KEY FK_D6E9C32E91BD8781');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A708DAFF');
        $this->addSql('ALTER TABLE election_candidate DROP FOREIGN KEY FK_D6E9C32EA708DAFF');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE election');
        $this->addSql('DROP TABLE election_candidate');
    }
}
