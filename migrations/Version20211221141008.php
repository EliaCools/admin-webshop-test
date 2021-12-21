<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221141008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_variation ADD parent_id INT DEFAULT NULL, ADD price NUMERIC(10, 2) NOT NULL');
        $query = 'SELECT p.id as id, p.price as price FROM product p';
        $data = $this->connection->prepare($query);
        $data2 =$data->executeQuery();
        foreach ($data2 as $product){
            $this->addSql('UPDATE TABLE product_variation set price = ' .$product["price"] . ' WHERE product_variation.product_id = ' . $product['id']);
        }
        $this->addSql('ALTER TABLE product DROP price');
        $this->addSql('ALTER TABLE product_variation ADD CONSTRAINT FK_C3B8567727ACA70 FOREIGN KEY (parent_id) REFERENCES product_variation (id)');
        $this->addSql('CREATE INDEX IDX_C3B8567727ACA70 ON product_variation (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD price NUMERIC(10, 3) NOT NULL');
        $this->addSql('ALTER TABLE product_variation DROP FOREIGN KEY FK_C3B8567727ACA70');
        $this->addSql('DROP INDEX IDX_C3B8567727ACA70 ON product_variation');
        $this->addSql('ALTER TABLE product_variation DROP parent_id, DROP price');
    }
}
