<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123142422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE candidate DROP CONSTRAINT fk_c8b28e4412eb0a51');
        $this->addSql('DROP INDEX idx_c8b28e4412eb0a51');
        $this->addSql('ALTER TABLE candidate DROP score_id');
        $this->addSql('ALTER TABLE score ADD candidate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375191BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3299375191BD8781 ON score (candidate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE candidate ADD score_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT fk_c8b28e4412eb0a51 FOREIGN KEY (score_id) REFERENCES score (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c8b28e4412eb0a51 ON candidate (score_id)');
        $this->addSql('ALTER TABLE score DROP CONSTRAINT FK_3299375191BD8781');
        $this->addSql('DROP INDEX IDX_3299375191BD8781');
        $this->addSql('ALTER TABLE score DROP candidate_id');
    }
}
