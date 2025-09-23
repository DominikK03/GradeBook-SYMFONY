<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250908144439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5792A9C15
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3D229E44B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F38361B064
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE presence
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lesson
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework DROP FOREIGN KEY FK_8C600B4EEA000B10
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8C600B4EEA000B10 ON homework
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework CHANGE class_id group_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework ADD CONSTRAINT FK_8C600B4EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8C600B4EFE54D947 ON homework (group_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, date DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT 'not_set' NOT NULL COLLATE `utf8mb4_unicode_ci`, lessonID INT NOT NULL, INDEX IDX_6977C7A5792A9C15 (lessonID), INDEX IDX_6977C7A5CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, topic VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, teacherID INT NOT NULL, classID INT NOT NULL, INDEX IDX_F87474F38361B064 (classID), INDEX IDX_F87474F3D229E44B (teacherID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5792A9C15 FOREIGN KEY (lessonID) REFERENCES lesson (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3D229E44B FOREIGN KEY (teacherID) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lesson ADD CONSTRAINT FK_F87474F38361B064 FOREIGN KEY (classID) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework DROP FOREIGN KEY FK_8C600B4EFE54D947
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8C600B4EFE54D947 ON homework
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework CHANGE group_id class_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework ADD CONSTRAINT FK_8C600B4EEA000B10 FOREIGN KEY (class_id) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8C600B4EEA000B10 ON homework (class_id)
        SQL);
    }
}
