<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260501184619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance (id INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, justification_date DATE DEFAULT NULL, coach_observation LONGTEXT DEFAULT NULL, player_id INT DEFAULT NULL, game_id INT DEFAULT NULL, training_id INT DEFAULT NULL, status_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, INDEX IDX_6DE30D9199E6F5DF (player_id), INDEX IDX_6DE30D91E48FD905 (game_id), INDEX IDX_6DE30D91BEFD98D1 (training_id), INDEX IDX_6DE30D916BF700BD (status_id), INDEX IDX_6DE30D913C105691 (coach_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D9199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D91BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D916BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D913C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D9199E6F5DF');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91E48FD905');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D91BEFD98D1');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D916BF700BD');
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D913C105691');
        $this->addSql('DROP TABLE attendance');
        $this->addSql('DROP TABLE status');
    }
}
