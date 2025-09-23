<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250828123051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD student_parent_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF3315140BF0 FOREIGN KEY (student_parent_id) REFERENCES student_parent (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B723AF3315140BF0 ON student (student_parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent ADD contact_email VARCHAR(255) DEFAULT NULL, ADD contact_phone VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF3315140BF0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B723AF3315140BF0 ON student
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP student_parent_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent DROP contact_email, DROP contact_phone
        SQL);
    }
}
