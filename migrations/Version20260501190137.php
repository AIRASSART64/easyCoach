<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260501190137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, total_quantity INT DEFAULT NULL, alert_level INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, type VARCHAR(255) NOT NULL, date DATE NOT NULL, reason LONGTEXT DEFAULT NULL, equipment_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, INDEX IDX_4B365660517FE9FE (equipment_id), INDEX IDX_4B3656603C105691 (coach_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656603C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660517FE9FE');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656603C105691');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE stock');
    }
}
