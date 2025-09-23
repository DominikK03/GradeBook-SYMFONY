<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807160127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE student_parent (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_B3B8B8CFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE student_parent_student (student_parent_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_D289F9CE15140BF0 (student_parent_id), INDEX IDX_D289F9CECB944F1A (student_id), PRIMARY KEY(student_parent_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent ADD CONSTRAINT FK_B3B8B8CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent_student ADD CONSTRAINT FK_D289F9CE15140BF0 FOREIGN KEY (student_parent_id) REFERENCES student_parent (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent_student ADD CONSTRAINT FK_D289F9CECB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent DROP FOREIGN KEY FK_B3B8B8CFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent_student DROP FOREIGN KEY FK_D289F9CE15140BF0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent_student DROP FOREIGN KEY FK_D289F9CECB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE student_parent
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE student_parent_student
        SQL);
    }
}
