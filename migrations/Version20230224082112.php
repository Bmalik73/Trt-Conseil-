<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224082112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_58DF0651A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C8B28E44A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_441282A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_application (id INT AUTO_INCREMENT NOT NULL, candidate_id INT NOT NULL, job_offer_id INT NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_C737C68891BD8781 (candidate_id), INDEX IDX_C737C6883481D195 (job_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_288A3A4E979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruiter (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DE8633D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_approved TINYINT(1) NOT NULL, user_type INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultant ADD CONSTRAINT FK_441282A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C68891BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE job_application ADD CONSTRAINT FK_C737C6883481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E979B1AD6 FOREIGN KEY (company_id) REFERENCES recruiter (id)');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651A76ED395');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A76ED395');
        $this->addSql('ALTER TABLE consultant DROP FOREIGN KEY FK_441282A1A76ED395');
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C68891BD8781');
        $this->addSql('ALTER TABLE job_application DROP FOREIGN KEY FK_C737C6883481D195');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E979B1AD6');
        $this->addSql('ALTER TABLE recruiter DROP FOREIGN KEY FK_DE8633D8A76ED395');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE consultant');
        $this->addSql('DROP TABLE job_application');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE recruiter');
        $this->addSql('DROP TABLE user');
    }
}
