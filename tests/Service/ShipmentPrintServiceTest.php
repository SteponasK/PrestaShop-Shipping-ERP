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

}