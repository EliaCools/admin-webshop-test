<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {

        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();


        $customer = new Customer();
        $customer->setFirstName("elia");
        $customer->setLastName("cools");
        $customer->setEmail('eliacools@hotmail.com');
        $customer->setCreatedAt(new \DateTimeImmutable("now"));
        $customer->setIsVerified(true);
        $customer->setOrderCount(0);
        $customer->setPassword( $this->userPasswordHasher->hashPassword(
            $customer,
            "test123"
        ));

        $customerAddress = new CustomerAddress();
        $customerAddress->setFirstName($customer->getFirstName())
            ->setLastName($customer->getLastName())
            ->setCity('Antwerpen')
            ->setCountry('belgië')
            ->setStreet('van de wervestraat')
            ->setHouseNumber('51')
            ->setUnitNumber('2')
            ->setZipcode('2060');
        $customer->addAddress($customerAddress);

        $manager->persist($customer);
        $manager->flush();


    }
}
