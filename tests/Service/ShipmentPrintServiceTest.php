<?php

namespace Tests\Invertus\Academy\ShipmentPrintService;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\ORM\EntityManagerInterface;

use Invertus\Academy\Entity\Shipment;
use Invertus\Academy\ShipmentPrintService\ShipmentPrintService;

use Dompdf\Dompdf;

class ShipmentPrintServiceTest extends KernelTestCase
{
    protected static $container;
    protected static EntityManagerInterface $entityManager;
    protected static array $shipmentInformation;
    public function setUp(): void
    {
        self::bootKernel();
        self::$container = self::getContainer();
        self::$entityManager = self::$container->get(EntityManagerInterface::class);
        self::$shipmentInformation = [
            'country' => 'Lithuania',
            'company' => 'Test Company',
            'firstName' => 'Joe',
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

    public function testGetShipment()
    {   
        $shipmentPrintService = self::$container->get(ShipmentPrintService::class);

        $shipment = new Shipment();
        $this->addShipmentInformation($shipment);
        self::$entityManager->persist($shipment);
        self::$entityManager->flush();

        $returnedShipment = $shipmentPrintService->getShipment(self::$entityManager, $shipment->getId());

        self::assertNotNull($returnedShipment);
        self::assertInstanceOf(Shipment::class, $returnedShipment);
        self::assertSame($shipment->getId(), $returnedShipment->getId());
    }

    public function testGetShipmentInformation()
    {
        $shipmentPrintService = self::$container->get(ShipmentPrintService::class);

        $shipment = new Shipment();
        $this->addShipmentInformation($shipment);

        $newShipmentInformation = $shipmentPrintService->getShipmentInformation($shipment);

        self::assertSame(self::$shipmentInformation, $newShipmentInformation);
    }

    public function testGeneratePdfFile()
    {
        $shipmentPrintService = self::$container->get(ShipmentPrintService::class);
        $pdf = $shipmentPrintService->generatePDfFile(self::$shipmentInformation);

        self::assertInstanceOf(Dompdf::class, $pdf);
    }

    private function addShipmentInformation($shipment): void
    {
        $shipment->setCountry(self::$shipmentInformation['country']);
        $shipment->setCompany(self::$shipmentInformation['company']);
        $shipment->setFirstName(self::$shipmentInformation['firstName']);
        $shipment->setLastName(self::$shipmentInformation['lastName']);
        $shipment->setAddress1(self::$shipmentInformation['address1']);
        $shipment->setAddress2(self::$shipmentInformation['address2']);
        $shipment->setPostcode(self::$shipmentInformation['postcode']);
        $shipment->setCity(self::$shipmentInformation['city']);
        $shipment->setPhone(self::$shipmentInformation['phone']);
        $shipment->setPhoneMobile(self::$shipmentInformation['phoneMobile']);
        $shipment->setBarcode(self::$shipmentInformation['barcode']);
    }
}