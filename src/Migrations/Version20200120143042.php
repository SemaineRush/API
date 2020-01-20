<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120143042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE elections (id INT AUTO_INCREMENT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, name VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidates (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, stylesheet LONGTEXT NOT NULL, infos LONGTEXT NOT NULL, INDEX IDX_6A77F80CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_elections (users_id INT NOT NULL, elections_id INT NOT NULL, INDEX IDX_7DAF15D267B3B43D (users_id), INDEX IDX_7DAF15D2CAD45A72 (elections_id), PRIMARY KEY(users_id, elections_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE election_has_candidates (id INT AUTO_INCREMENT NOT NULL, candidate_id INT DEFAULT NULL, election_id INT DEFAULT NULL, vote INT NOT NULL, INDEX IDX_8261101D91BD8781 (candidate_id), INDEX IDX_8261101DA708DAFF (election_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidates ADD CONSTRAINT FK_6A77F80CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_elections ADD CONSTRAINT FK_7DAF15D267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_elections ADD CONSTRAINT FK_7DAF15D2CAD45A72 FOREIGN KEY (elections_id) REFERENCES elections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE election_has_candidates ADD CONSTRAINT FK_8261101D91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id)');
        $this->addSql('ALTER TABLE election_has_candidates ADD CONSTRAINT FK_8261101DA708DAFF FOREIGN KEY (election_id) REFERENCES elections (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_elections DROP FOREIGN KEY FK_7DAF15D2CAD45A72');
        $this->addSql('ALTER TABLE election_has_candidates DROP FOREIGN KEY FK_8261101DA708DAFF');
        $this->addSql('ALTER TABLE election_has_candidates DROP FOREIGN KEY FK_8261101D91BD8781');
        $this->addSql('ALTER TABLE candidates DROP FOREIGN KEY FK_6A77F80CA76ED395');
        $this->addSql('ALTER TABLE users_elections DROP FOREIGN KEY FK_7DAF15D267B3B43D');
        $this->addSql('DROP TABLE elections');
        $this->addSql('DROP TABLE candidates');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_elections');
        $this->addSql('DROP TABLE election_has_candidates');
    }
}
