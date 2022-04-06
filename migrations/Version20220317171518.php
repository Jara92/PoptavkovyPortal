<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317171518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_rating (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1FBF396750 FOREIGN KEY (id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating DROP author_name, CHANGE author_id author_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_rating');
        $this->addSql('ALTER TABLE rating ADD author_name VARCHAR(255) DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
    }
}
