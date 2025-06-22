<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622220017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, update_at DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, external_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor ADD specialty_id INT DEFAULT NULL, DROP speciality, DROP description
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1FC0F36A9A353316 ON doctor (specialty_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE specialty
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36A9A353316
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1FC0F36A9A353316 ON doctor
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor ADD speciality VARCHAR(255) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL, DROP specialty_id
        SQL);
    }
}
