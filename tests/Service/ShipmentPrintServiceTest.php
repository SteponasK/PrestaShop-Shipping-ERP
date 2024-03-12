<?php

namespace Tests\Invertus\Academy\ShipmentPrintServiceTest;

require_once './src/Service/ShipmentPrintService.php';

use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Invertus\Academy\Entity\Shipment;
use Invertus\Academy\ShipmentPrintService\ShipmentPrintService;
use PHPUnit\Framework\TestCase;

class ShipmentPrintServiceTest extends TestCase
{

    public function testGetShipment()
    {
        $shipment = new Shipment();

        $mockRepository = $this->createMock(\Doctrine\Persistence\ObjectRepository::class);

        $mockRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($shipment);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockRepository);

        $shipmentPrintService = new ShipmentPrintService();

        $result = $shipmentPrintService->getShipment($entityManager, 1);

        $this->assertInstanceOf(Shipment::class, $result);
    }

    public function testGetShipmentInformation()
    {
        $shipment = new Shipment;
        $shipment->setCountry('country1');
        $shipment->setCompany('company2');
        $shipment->setFirstName('firstName3');
        $shipment->setLastName('lastName4');
        $shipment->setAddress1('address15');
        $shipment->setAddress2('address26');
        $shipment->setPostcode('postcode7');
        $shipment->setCity('city8');
        $shipment->setPhone('phone9');
        $shipment->setPhoneMobile('phoneMobile10');
        $shipment->setBarcode(10101);

        $shipmentPrintService = new ShipmentPrintService();
        $shipmentInformation = $shipmentPrintService->getShipmentInformation($shipment);

        $this->assertEquals(
            [
                'country'=> 'country1',
                'company' => 'company2',
                'firstName' => 'firstName3',
                'lastName' => 'lastName4',
                'address1' => 'address15',
                'address2'=> 'address26',
                'postcode' => 'postcode7',
                'city' => 'city8',
                'phone' => 'phone9',
                'phoneMobile' => 'phoneMobile10',
                'barcode' => '10101'
            ]
            , $shipmentInformation);
    }

    public function testGeneratePdfFile()
    {
        $shipmentPrintService = new ShipmentPrintService();

        $shipmentInformation = [
            'country' => 'Great Britain',
            'barcode' => '10101'
        ];
        $this->assertInstanceOf(Dompdf::class, $shipmentPrintService->generatePdfFile($shipmentInformation));
    }

}