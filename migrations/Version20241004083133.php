<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004083133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_user (company_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CEFECCA7979B1AD6 (company_id), INDEX IDX_CEFECCA7A76ED395 (user_id), PRIMARY KEY(company_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_user ADD CONSTRAINT FK_CEFECCA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9979B1AD6 ON projet (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_user DROP FOREIGN KEY FK_CEFECCA7979B1AD6');
        $this->addSql('ALTER TABLE company_user DROP FOREIGN KEY FK_CEFECCA7A76ED395');
        $this->addSql('DROP TABLE company_user');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9979B1AD6');
        $this->addSql('DROP INDEX IDX_50159CA9979B1AD6 ON projet');
        $this->addSql('ALTER TABLE projet DROP company_id');
    }
}
