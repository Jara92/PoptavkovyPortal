<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314200411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inquiry_signed_request ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE inquiry_signed_request ADD CONSTRAINT FK_3264AEA8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3264AEA8A76ED395 ON inquiry_signed_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inquiry_signed_request DROP FOREIGN KEY FK_3264AEA8A76ED395');
        $this->addSql('DROP INDEX IDX_3264AEA8A76ED395 ON inquiry_signed_request');
        $this->addSql('ALTER TABLE inquiry_signed_request DROP user_id');
    }
}
