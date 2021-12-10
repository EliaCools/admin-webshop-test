<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208204103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_group_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FE4FBB8262D643B7 (attribute_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variation (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_C3B85674584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variation_attribute_value (product_variation_id INT NOT NULL, attribute_value_id INT NOT NULL, INDEX IDX_4478A10EDC269DB3 (product_variation_id), INDEX IDX_4478A10E65A22152 (attribute_value_id), PRIMARY KEY(product_variation_id, attribute_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute_value ADD CONSTRAINT FK_FE4FBB8262D643B7 FOREIGN KEY (attribute_group_id) REFERENCES attribute_group (id)');
        $this->addSql('ALTER TABLE product_variation ADD CONSTRAINT FK_C3B85674584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variation_attribute_value ADD CONSTRAINT FK_4478A10EDC269DB3 FOREIGN KEY (product_variation_id) REFERENCES product_variation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variation_attribute_value ADD CONSTRAINT FK_4478A10E65A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE price price NUMERIC(10, 3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_value DROP FOREIGN KEY FK_FE4FBB8262D643B7');
        $this->addSql('ALTER TABLE product_variation_attribute_value DROP FOREIGN KEY FK_4478A10E65A22152');
        $this->addSql('ALTER TABLE product_variation_attribute_value DROP FOREIGN KEY FK_4478A10EDC269DB3');
        $this->addSql('DROP TABLE attribute_group');
        $this->addSql('DROP TABLE attribute_value');
        $this->addSql('DROP TABLE product_variation');
        $this->addSql('DROP TABLE product_variation_attribute_value');
        $this->addSql('ALTER TABLE product CHANGE price price NUMERIC(10, 2) NOT NULL');
    }
}
