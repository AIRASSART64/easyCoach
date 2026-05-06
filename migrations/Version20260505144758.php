<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505144758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance CHANGE start_date start_date DATE DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_attendance_training ON attendance (player_id, training_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_attendance_game ON attendance (player_id, game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_attendance_training ON attendance');
        $this->addSql('DROP INDEX unique_attendance_game ON attendance');
        $this->addSql('ALTER TABLE attendance CHANGE start_date start_date DATE NOT NULL');
    }
}
