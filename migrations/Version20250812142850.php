<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250812142850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE grade DROP FOREIGN KEY FK_595AAE34CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_595AAE34CB944F1A ON grade
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade DROP student_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE grade ADD student_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade ADD CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_595AAE34CB944F1A ON grade (student_id)
        SQL);
    }
}
