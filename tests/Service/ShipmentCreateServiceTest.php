<?php

namespace Tests\Invertus\Academy\ShipmentCreateService;

use Doctrine\ORM\EntityManagerInterface;
use Invertus\Academy\Entity\Shipment;
use Invertus\Academy\ShipmentCreateService\ShipmentCreateService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShipmentCreateServiceTest extends KernelTestCase
{
    protected static $container;
    protected static EntityManagerInterface $entityManager;
    protected static array $shipmentInformation;

    public function setUp(): void
    {
        self::bootKernel();
        self::$container = static::getContainer();
        self::$entityManager = self::$container->get(EntityManagerInterface::class);
        self::$entityManager->beginTransaction();
        
        self::$shipmentInformation = [
            'country' => 'Lithuania',
            'company' => 'Test Company',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'address1' => '123 Main St.',
            'address2' => 'Apt 101',
            'postcode' => '12345',
            'city' => 'Anytown',
            'phone' => '123-456-7890',
            'phoneMobile' => '+3706123123',
            'barcode' => '10101'
        ];
    }

    public function tearDown(): void
    {
        self::$entityManager->rollback();
    }

    public function testCreateShipment()
    {
        $shipmentCreateService = self::$container->get(ShipmentCreateService::class);
        $shipmentCreateService->createShipment(self::$shipmentInformation, self::$entityManager);

        $shipment = self::$entityManager->getRepository(Shipment::class)->findOneBy([
        'firstName' => 'John',
    ]);
        self::assertNotNull($shipment);

        foreach (self::$shipmentInformation as $key => $value)
        {
            $getterMethod = 'get' . ucfirst($key);
            self::assertEquals($value, $shipment->$getterMethod());
        }
    }
}