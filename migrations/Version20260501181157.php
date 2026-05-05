<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260501181157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT NULL, opposing_team VARCHAR(255) DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, score_home INT DEFAULT NULL, score_away INT DEFAULT NULL, coach_observation LONGTEXT DEFAULT NULL, team_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, INDEX IDX_232B318C296CD8AE (team_id), INDEX IDX_232B318C3C105691 (coach_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C3C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C296CD8AE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C3C105691');
        $this->addSql('DROP TABLE game');
    }
}
