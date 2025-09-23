<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905103523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE homework (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, class_id INT NOT NULL, subject VARCHAR(255) NOT NULL, due_date DATE NOT NULL, description VARCHAR(255) DEFAULT NULL, topic VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_8C600B4E41807E1D (teacher_id), INDEX IDX_8C600B4EEA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework ADD CONSTRAINT FK_8C600B4E41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework ADD CONSTRAINT FK_8C600B4EEA000B10 FOREIGN KEY (class_id) REFERENCES `group` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE homework DROP FOREIGN KEY FK_8C600B4E41807E1D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE homework DROP FOREIGN KEY FK_8C600B4EEA000B10
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE homework
        SQL);
    }
}
