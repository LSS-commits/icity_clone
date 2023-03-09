<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225141429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE infrastructure_categorie (infrastructure_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_71D85A92243E7A84 (infrastructure_id), INDEX IDX_71D85A92BCF5E72D (categorie_id), PRIMARY KEY(infrastructure_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infrastructure_categorie ADD CONSTRAINT FK_71D85A92243E7A84 FOREIGN KEY (infrastructure_id) REFERENCES infrastructure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE infrastructure_categorie ADD CONSTRAINT FK_71D85A92BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE infrastructure_categorie');
    }
}
