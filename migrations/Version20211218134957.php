<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Interfaces\EntityManagerAwareInterface;
use App\Repository\ProductRepository;
use App\Repository\ProductVariationRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218134957 extends AbstractMigration implements EntityManagerAwareInterface
{
    private EntityManager $entityManager;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_variation ADD sku VARCHAR(255) NOT NULL');

        $productRepository = $this->entityManager->getRepository('App:Product');

       // $allProducts = $productRepository->findAll();

        $query = 'SELECT p.name, p.id, pv.id as pvid, av.name as avname from product_variation as pv LEFT JOIN product as p on p.id = pv.product_id LEFT JOIN product_variation_attribute_value pvav on pv.id = pvav.product_variation_id LEFT JOIN attribute_value av on pvav.attribute_value_id = av.id';
        $data = $this->connection->prepare($query);
        $data2 =$data->executeQuery();

        $allProductVariants = $data2->fetchAllAssociative();

        foreach ($allProductVariants as $product){
            //foreach ($product['pvname'] as $productVariation) {
                // 3 first letters of product name, id, color , size
                $productName = substr($product['name'], 0, 3);

                $productId = $product['id'];

                $attributeValue = $product["avname"] ?? "base";

                $skus = $productName . $productId . "-" . $attributeValue  . "-" . $product["pvid"];

                $this->addSql("UPDATE product_variation as pv SET pv.sku= '" . $skus ."' WHERE id = " . $product['pvid']);

           // }
        }

        $this->addSql('CREATE UNIQUE INDEX UNIQ_C3B8567F9038C4 ON product_variation (sku)');
   }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
         $this->addSql('DROP INDEX UNIQ_C3B8567F9038C4 ON product_variation');
        $this->addSql('ALTER TABLE product_variation DROP sku');
    }

    public function setEntityManager(EntityManager $entityManager = null)
    {
        $this->entityManager = $entityManager;
    }
}
