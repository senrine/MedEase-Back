<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240608081157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patient_id INTEGER NOT NULL, professional_id INTEGER NOT NULL, schedule_id INTEGER NOT NULL, CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F844DB77003 FOREIGN KEY (professional_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FE38F844A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FE38F8446B899279 ON appointment (patient_id)');
        $this->addSql('CREATE INDEX IDX_FE38F844DB77003 ON appointment (professional_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE38F844A40BC2D5 ON appointment (schedule_id)');
        $this->addSql('CREATE TABLE bill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, link VARCHAR(255) NOT NULL, patient_id INTEGER NOT NULL, professional_id INTEGER NOT NULL, CONSTRAINT FK_7A2119E36B899279 FOREIGN KEY (patient_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7A2119E3DB77003 FOREIGN KEY (professional_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A2119E36B899279 ON bill (patient_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A2119E3DB77003 ON bill (professional_id)');
        $this->addSql('CREATE TABLE schedule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, day DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, professional_id INTEGER NOT NULL, CONSTRAINT FK_5A3811FBDB77003 FOREIGN KEY (professional_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5A3811FBDB77003 ON schedule (professional_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, patient BOOLEAN NOT NULL, professional BOOLEAN NOT NULL, speciality VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
