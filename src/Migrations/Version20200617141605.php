<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617141605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE type_document DROP FOREIGN KEY FK_1596AD8A62182743');
        $this->addSql('DROP INDEX IDX_1596AD8A62182743 ON type_document');
        $this->addSql('ALTER TABLE type_document DROP demande_document_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE type_document ADD demande_document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_document ADD CONSTRAINT FK_1596AD8A62182743 FOREIGN KEY (demande_document_id) REFERENCES demande_document (id)');
        $this->addSql('CREATE INDEX IDX_1596AD8A62182743 ON type_document (demande_document_id)');
    }
}
