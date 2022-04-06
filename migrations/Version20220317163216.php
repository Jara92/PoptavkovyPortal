<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317163216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inquiring_rating (id INT NOT NULL, inquiry_id INT NOT NULL, UNIQUE INDEX UNIQ_BB697764A7AD6D71 (inquiry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_rating (id INT NOT NULL, inquiry_id INT NOT NULL, realized_inquiry TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_3EBF4F6A7AD6D71 (inquiry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB697764A7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE inquiring_rating ADD CONSTRAINT FK_BB697764BF396750 FOREIGN KEY (id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6A7AD6D71 FOREIGN KEY (inquiry_id) REFERENCES inquiry (id)');
        $this->addSql('ALTER TABLE supplier_rating ADD CONSTRAINT FK_3EBF4F6BF396750 FOREIGN KEY (id) REFERENCES rating (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE inquiring_rating');
        $this->addSql('DROP TABLE supplier_rating');
    }
}
