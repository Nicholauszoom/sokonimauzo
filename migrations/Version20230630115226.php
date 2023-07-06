<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630115226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E966278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E964D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96FFA0C224 FOREIGN KEY (office_id) REFERENCES officess (id)');
        $this->addSql('CREATE INDEX IDX_DB021E966278D5A8 ON messages (classroom_id)');
        $this->addSql('CREATE INDEX IDX_DB021E964D2A7E12 ON messages (building_id)');
        $this->addSql('CREATE INDEX IDX_DB021E96FFA0C224 ON messages (office_id)');
        $this->addSql('ALTER TABLE task DROP INDEX FK_527EDB25F16DFE2B, ADD UNIQUE INDEX UNIQ_527EDB25F16DFE2B (techn_id)');
        $this->addSql('ALTER TABLE task CHANGE status status TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25537A1329 FOREIGN KEY (message_id) REFERENCES messages (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25537A1329 ON task (message_id)');
        $this->addSql('ALTER TABLE technician CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE staff');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E966278D5A8');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E964D2A7E12');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96FFA0C224');
        $this->addSql('DROP INDEX IDX_DB021E966278D5A8 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E964D2A7E12 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E96FFA0C224 ON messages');
        $this->addSql('ALTER TABLE task DROP INDEX UNIQ_527EDB25F16DFE2B, ADD INDEX FK_527EDB25F16DFE2B (techn_id)');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25537A1329');
        $this->addSql('DROP INDEX UNIQ_527EDB25537A1329 ON task');
        $this->addSql('ALTER TABLE task CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE technician CHANGE roles roles LONGTEXT NOT NULL COMMENT \'  (DC2Type:json)\'');
    }
}
