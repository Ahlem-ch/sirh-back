<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302094920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conge ADD type_conge_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348753BDA5 FOREIGN KEY (type_conge_id) REFERENCES type_conge (id)');
        $this->addSql('CREATE INDEX IDX_2ED89348753BDA5 ON conge (type_conge_id)');
        $this->addSql('ALTER TABLE type_conge DROP FOREIGN KEY FK_20D414BFCAAC9A59');
        $this->addSql('DROP INDEX IDX_20D414BFCAAC9A59 ON type_conge');
        $this->addSql('ALTER TABLE type_conge DROP conge_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348753BDA5');
        $this->addSql('DROP INDEX IDX_2ED89348753BDA5 ON conge');
        $this->addSql('ALTER TABLE conge ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_conge_id');
        $this->addSql('ALTER TABLE type_conge ADD conge_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_conge ADD CONSTRAINT FK_20D414BFCAAC9A59 FOREIGN KEY (conge_id) REFERENCES conge (id)');
        $this->addSql('CREATE INDEX IDX_20D414BFCAAC9A59 ON type_conge (conge_id)');
    }
}
