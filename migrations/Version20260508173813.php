<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260508173813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_category ADD coach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_category ADD CONSTRAINT FK_BE0EC5BF3C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BE0EC5BF3C105691 ON team_category (coach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_category DROP FOREIGN KEY FK_BE0EC5BF3C105691');
        $this->addSql('DROP INDEX IDX_BE0EC5BF3C105691 ON team_category');
        $this->addSql('ALTER TABLE team_category DROP coach_id');
    }
}
