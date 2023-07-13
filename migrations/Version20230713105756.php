<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713105756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_accounts (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, account_number VARCHAR(255) NOT NULL, balance VARCHAR(255) NOT NULL COMMENT \'(DC2Type:money)\', currency VARCHAR(3) NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_FB88842BB1A4D127 (account_number), UNIQUE INDEX UNIQ_FB88842BD17F50A6 (uuid), INDEX IDX_FB88842B9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, social_security_number VARCHAR(255) NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_62534E21BF52BEC2 (social_security_number), UNIQUE INDEX UNIQ_62534E21D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_accounts ADD CONSTRAINT FK_FB88842B9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_accounts DROP FOREIGN KEY FK_FB88842B9395C3F3');
        $this->addSql('DROP TABLE bank_accounts');
        $this->addSql('DROP TABLE customers');
    }
}
