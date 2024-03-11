<?php

namespace Invertus\Academy\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Invertus\Academy\ApiHelper\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use Invertus\Academy\ShipmentCreateService\ShipmentCreateService;
use Invertus\Academy\ShipmentPrintService\ShipmentPrintService;

class ShipmentController extends AbstractController
{ 
    #[Route('/api/shipment/save/', name: 'app_save_shipment', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager, ShipmentCreateService $service, ApiHelper $apiHelper): Response
    {
        if (!$apiHelper->isApiKeyValid($request))
        {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $apiHelper->getData($request);
        $service->createShipment($data, $entityManager);
        
        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, ShipmentPrintService $service, ApiHelper $apiHelper, int $id): Response
    {
        if (!$apiHelper->isApiKeyValid($request))
        {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }

        $shipment = $service->getShipment($entityManager, $id);

        if (!$shipment)
        {
            throw $this->createNotFoundException('No product found for id '. $id);
        }

        $shipmentInformation = $service->getShipmentInformation($shipment);

        $pdf = $service->generatePdfFile($shipmentInformation);

       return new Response($pdf->output(), Response::HTTP_OK, ['Content-Type' => 'application/pdf',]);
    }  
}
