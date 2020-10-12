<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611122248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE config_autorisation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE config_autorisation (id INT AUTO_INCREMENT NOT NULL, nb_autorisation_id INT NOT NULL, nombre DOUBLE PRECISION NOT NULL, duree DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_DAA09043FD8196D2 (nb_autorisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE config_autorisation ADD CONSTRAINT FK_DAA09043FD8196D2 FOREIGN KEY (nb_autorisation_id) REFERENCES autorisation_sortie (id)');
    }
}
