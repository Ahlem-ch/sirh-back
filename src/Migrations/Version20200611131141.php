<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611131141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE config_autorisation DROP FOREIGN KEY FK_DAA09043FD8196D2');
        $this->addSql('DROP INDEX UNIQ_DAA09043FD8196D2 ON config_autorisation');
        $this->addSql('ALTER TABLE config_autorisation DROP nb_autorisation_id, CHANGE nombre nb_autorisation DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE config_autorisation ADD nb_autorisation_id INT NOT NULL, CHANGE nb_autorisation nombre DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE config_autorisation ADD CONSTRAINT FK_DAA09043FD8196D2 FOREIGN KEY (nb_autorisation_id) REFERENCES autorisation_sortie (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DAA09043FD8196D2 ON config_autorisation (nb_autorisation_id)');
    }
}
