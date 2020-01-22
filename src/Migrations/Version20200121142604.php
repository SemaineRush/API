<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121142604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE election_user (election_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_11DCA16FA708DAFF (election_id), INDEX IDX_11DCA16FA76ED395 (user_id), PRIMARY KEY(election_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE election_user ADD CONSTRAINT FK_11DCA16FA708DAFF FOREIGN KEY (election_id) REFERENCES election (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE election_user ADD CONSTRAINT FK_11DCA16FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A708DAFF');
        $this->addSql('DROP INDEX IDX_8D93D649A708DAFF ON user');
        $this->addSql('ALTER TABLE user DROP election_id');
        $this->addSql('ALTER TABLE candidate ADD nb_votes INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE election_user');
        $this->addSql('ALTER TABLE candidate DROP nb_votes');
        $this->addSql('ALTER TABLE user ADD election_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A708DAFF ON user (election_id)');
    }
}
