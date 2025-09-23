<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250811113259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE person DROP FOREIGN KEY FK_34DCD176A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_34DCD176A76ED395 ON person
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD person_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student ADD CONSTRAINT FK_B723AF33217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B723AF33217BBB47 ON student (person_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent ADD person_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent ADD CONSTRAINT FK_B3B8B8CF217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B3B8B8CF217BBB47 ON student_parent (person_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher ADD person_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B0F6A6D5217BBB47 ON teacher (person_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5217BBB47
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B0F6A6D5217BBB47 ON teacher
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teacher DROP person_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_34DCD176A76ED395 ON person (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP FOREIGN KEY FK_B723AF33217BBB47
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B723AF33217BBB47 ON student
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student DROP person_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent DROP FOREIGN KEY FK_B3B8B8CF217BBB47
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_B3B8B8CF217BBB47 ON student_parent
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE student_parent DROP person_id
        SQL);
    }
}
