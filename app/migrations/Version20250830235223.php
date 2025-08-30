<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830235223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF34668989D9B62 ON categories (slug)');
        $this->addSql('ALTER TABLE comment RENAME INDEX idx_9474526c59d8a214 TO idx_comments_recipe_id');
        $this->addSql('CREATE INDEX idx_recipes_created_at ON recipe (created_at)');
        $this->addSql('ALTER TABLE recipe RENAME INDEX idx_da88b13712469de2 TO idx_recipes_category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3AF34668989D9B62 ON categories');
        $this->addSql('DROP INDEX idx_recipes_created_at ON recipe');
        $this->addSql('ALTER TABLE recipe RENAME INDEX idx_recipes_category_id TO IDX_DA88B13712469DE2');
        $this->addSql('ALTER TABLE comment RENAME INDEX idx_comments_recipe_id TO IDX_9474526C59D8A214');
    }
}
