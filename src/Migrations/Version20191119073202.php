<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119073202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student ADD niveau_id INT DEFAULT NULL, ADD filiere_id INT DEFAULT NULL, ADD universite_id INT DEFAULT NULL, ADD datenaissance VARCHAR(255) NOT NULL, ADD lieunaissance VARCHAR(255) NOT NULL, ADD matricule VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF332A52F05F FOREIGN KEY (universite_id) REFERENCES universite (id)');
        $this->addSql('CREATE INDEX IDX_B723AF33B3E9C81 ON student (niveau_id)');
        $this->addSql('CREATE INDEX IDX_B723AF33180AA129 ON student (filiere_id)');
        $this->addSql('CREATE INDEX IDX_B723AF332A52F05F ON student (universite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33B3E9C81');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33180AA129');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF332A52F05F');
        $this->addSql('DROP INDEX IDX_B723AF33B3E9C81 ON student');
        $this->addSql('DROP INDEX IDX_B723AF33180AA129 ON student');
        $this->addSql('DROP INDEX IDX_B723AF332A52F05F ON student');
        $this->addSql('ALTER TABLE student DROP niveau_id, DROP filiere_id, DROP universite_id, DROP datenaissance, DROP lieunaissance, DROP matricule');
    }
}
