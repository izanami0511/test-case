<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622113932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, speciality VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, schedule JSON DEFAULT NULL, profile_id INT NOT NULL, UNIQUE INDEX UNIQ_1FC0F36ACCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, phone VARCHAR(16) DEFAULT NULL, birthdate VARCHAR(32) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL
        );
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36ACCFA12B8 FOREIGN KEY (profile_id) REFERENCES `user` (id)
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36ACCFA12B8
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE doctor
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL
        );
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL
        );
    }
}
