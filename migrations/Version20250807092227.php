<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807092227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(50) NOT NULL, city VARCHAR(100) NOT NULL, postal_code VARCHAR(6) NOT NULL, street VARCHAR(100) NOT NULL, voivodeship VARCHAR(100) NOT NULL, address_number VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, teacher_id INT NOT NULL, value INT NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, INDEX IDX_595AAE34CB944F1A (student_id), INDEX IDX_595AAE3441807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, school_year VARCHAR(9) NOT NULL, name VARCHAR(255) NOT NULL, groupTeacherID INT NOT NULL, UNIQUE INDEX UNIQ_6DC044C577286FF7 (groupTeacherID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, topic VARCHAR(100) NOT NULL, subject VARCHAR(255) NOT NULL, teacherID INT NOT NULL, classID INT NOT NULL, INDEX IDX_F87474F3D229E44B (teacherID), INDEX IDX_F87474F38361B064 (classID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, address_id INT NOT NULL, first_name VARCHAR(20) NOT NULL, last_name VARCHAR(40) NOT NULL, pesel VARCHAR(11) NOT NULL, birth_date DATE NOT NULL, phone_number VARCHAR(11) DEFAULT NULL, second_name VARCHAR(40) DEFAULT NULL, nationality VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_34DCD1763931747B (pesel), UNIQUE INDEX UNIQ_34DCD176A76ED395 (user_id), INDEX IDX_34DCD176F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, date DATETIME NOT NULL, status VARCHAR(255) DEFAULT 'not_set' NOT NULL, lessonID INT NOT NULL, INDEX IDX_6977C7A5CB944F1A (student_id), INDEX IDX_6977C7A5792A9C15 (lessonID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, class_id INT NOT NULL, hour TIME NOT NULL, classroom VARCHAR(255) NOT NULL, weekday VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, teacherID INT NOT NULL, INDEX IDX_5A3811FBD229E44B (teacherID), INDEX IDX_5A3811FBEA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, groupID INT NOT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF33D6EFA878 (groupID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, specialization LONGTEXT NOT NULL COMMENT '(DC2Type:simple_array)', UNIQUE INDEX UNIQ_B0F6A6D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade ADD CONSTRAINT FK_595AAE3441807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C577286FF7 FOREIGN KEY (groupTeacherID) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3D229E44B FOREIGN KEY (teacherID) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson ADD CONSTRAINT FK_F87474F38361B064 FOREIGN KEY (classID) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person ADD CONSTRAINT FK_34DCD176F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5792A9C15 FOREIGN KEY (lessonID) REFERENCES lesson (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBD229E44B FOREIGN KEY (teacherID) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBEA000B10 FOREIGN KEY (class_id) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF33D6EFA878 FOREIGN KEY (groupID) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade DROP FOREIGN KEY FK_595AAE3441807E1D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C577286FF7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3D229E44B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F38361B064
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person DROP FOREIGN KEY FK_34DCD176A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person DROP FOREIGN KEY FK_34DCD176F5B7AF75
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5792A9C15
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBD229E44B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBEA000B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF33D6EFA878
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE grade
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `group`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lesson
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE person
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE presence
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE student
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE teacher
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
