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
        $id = $shipmentCreateService->createShipment($data, $entityManager);
        
        return new Response($id, Response::HTTP_CREATED, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);

    }

    #[Route('/api/shipment/save/', name: 'app_save_options', methods: ['OPTIONS'])]
    public function saveOptions(): Response
    {
        return new Response('',Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, ShipmentPrintService $shipmentPrintService, ApiHelper $apiHelper, int $id): Response
    {
        if (!$apiHelper->isApiKeyValid($request))
        {
           return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }

        $shipment = $shipmentPrintService->getShipment($entityManager, $id);

        if (!$shipment)
        {
            throw $this->createNotFoundException('No product found for id '. $id);
        }

        $shipmentInformation = $shipmentPrintService->getShipmentInformation($shipment);

        $pdf = $shipmentPrintService->generatePdfFile($shipmentInformation);


        return new Response(base64_encode($pdf->output()), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="shipment_label.pdf"',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);
    }  
    
    #[Route('/api/shipment/print/{id}', name: 'app_print_options', methods: ['OPTIONS'])]
    public function printOptions(int $id): Response
    {
        return new Response('',Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ]);
    }
}
