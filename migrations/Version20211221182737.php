<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221182737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql('ALTER TABLE product_image ADD relative_path_from_server_image_folder VARCHAR(100) NOT NULL, ADD file_extension VARCHAR(10), CHANGE directory_path filename VARCHAR(255) NOT NULL');

        $query = 'SELECT pi.directory_path as filename, pi.id as id FROM product_image as pi';
        $data = $this->connection->prepare($query);
        $data2 =$data->executeQuery();

        foreach($data2->fetchAllAssociative() as $productImage){
           $fileParts =  pathinfo($productImage['filename']);
           $fileName = $fileParts['filename'];
           $extension = $fileParts['extension'];

           $this->addSql('UPDATE product_image as pi SET pi.filename ="' .$fileName .'", pi.file_extension ="' . $extension . '", pi.relative_path_from_server_image_folder = "products/" WHERE pi.id=' . $productImage['id'] );
        }

        $this->addSql('ALTER TABLE product_image MODIFY file_extension VARCHAR(10) NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP relative_path_from_server_image_folder, DROP file_extension, CHANGE file_name directory_path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
