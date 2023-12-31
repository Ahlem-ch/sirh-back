<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617141736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande_document ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE demande_document ADD CONSTRAINT FK_9E30C3B4C54C8C93 FOREIGN KEY (type_id) REFERENCES type_document (id)');
        $this->addSql('CREATE INDEX IDX_9E30C3B4C54C8C93 ON demande_document (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demande_document DROP FOREIGN KEY FK_9E30C3B4C54C8C93');
        $this->addSql('DROP INDEX IDX_9E30C3B4C54C8C93 ON demande_document');
        $this->addSql('ALTER TABLE demande_document DROP type_id');
    }
}
