<?php

namespace Invertus\Academy\Controller;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Invertus\Academy\ShipmentCreateService\ShipmentCreateService;
use Invertus\Academy\ShipmentPrintService\ShipmentPrintService;
use Invertus\Academy\ApiHelper\ApiHelper;

class ShipmentController extends AbstractController
{ 
    #[Route('/api/shipment/save/', name: 'app_save_shipment', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, ShipmentCreateService $shipmentCreateService, ApiHelper $apiHelper): Response
    {
        if (!$apiHelper->isApiKeyValid($request))
        {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $apiHelper->getData($request);
        $shipmentCreateService->createShipment($data, $entityManager);
        
        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, ShipmentPrintService $shipmentCreateService, ApiHelper $apiHelper, int $id): Response
    {
        if (!$apiHelper->isApiKeyValid($request))
        {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }

        $shipment = $shipmentCreateService->getShipment($entityManager, $id);

        if (!$shipment)
        {
            throw $this->createNotFoundException('No product found for id '. $id);
        }

        $shipmentInformation = $shipmentCreateService->getShipmentInformation($shipment);

        $pdf = $shipmentCreateService->generatePdfFile($shipmentInformation);

       return new Response($pdf->output(), Response::HTTP_OK, ['Content-Type' => 'application/pdf',]);
    }  
}
