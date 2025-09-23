<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250907093350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add CHECK constraints for Grade value (1-6) and wage (1-3)';
    }

    public function up(Schema $schema): void
    {
        // Add CHECK constraints for Grade validation
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT grade_value_check CHECK (value >= 1 AND value <= 6)');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT grade_wage_check CHECK (wage >= 1 AND wage <= 3)');
    }

    public function down(Schema $schema): void
    {
        // Remove CHECK constraints
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT grade_value_check');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT grade_wage_check');
    }
}
