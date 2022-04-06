<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317162334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622158E0B66');
        $this->addSql('DROP INDEX IDX_D8892622158E0B66 ON rating');
        $this->addSql('ALTER TABLE rating ADD target VARCHAR(255) DEFAULT NULL, DROP target_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating ADD target_id INT DEFAULT NULL, DROP target');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622158E0B66 FOREIGN KEY (target_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D8892622158E0B66 ON rating (target_id)');
    }
}
