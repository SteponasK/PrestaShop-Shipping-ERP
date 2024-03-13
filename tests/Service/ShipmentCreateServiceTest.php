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
    protected static array $shipmentData;

    public function setUp(): void
    {
        self::bootKernel();
        self::$container = static::getContainer();
        self::$entityManager = self::$container->get(EntityManagerInterface::class);
        
        self::$shipmentData = [
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
        ];
    }
    public function testCreateShipment()
    {
        $shipmentCreateService = self::$container->get(ShipmentCreateService::class);
        $shipmentCreateService->createShipment(self::$shipmentData, self::$entityManager);

        $shipment = self::$entityManager->getRepository(Shipment::class)->findOneBy([
        'firstName' => 'John',
    ]);
        self::assertNotNull($shipment);
        self::assertEquals(self::$shipmentData['firstName'], $shipment->getFirstName());
        self::assertEquals(self::$shipmentData['address1'], $shipment->getAddress1());
    }
}