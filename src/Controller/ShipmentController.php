<?php

namespace Invertus\Academy\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Invertus\Academy\Entity\Product;
use Invertus\Academy\Entity\Shipment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShipmentController extends AbstractController
{ 
    #[Route('/api/shipment/save/', name: 'app_save_shipment', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
 
        $data = json_decode($request->getContent(), true);
        $this->createShipmentService($data, $entityManager);
        
        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        
        $shipment = $entityManager->getRepository(Shipment::class)->find($id);

        if(!$shipment){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $shipmentInformation = $this->getShipmentInformation($shipment);

        $pdfString = $this->generatePdfString($shipment);

        return new Response();
    }  

    private function generatePdfString(Shipment $shipment): string
    {

        return '';
    }
    
    private function getShipmentInformation(Shipment $shipment): array
    {
        return [
            'country'=> $shipment->getCountry(),
            'company' => $shipment->getCompany(),
            'firstName' => $shipment->getFirstName(),
            'lastName' => $shipment->getLastName(),
            'address1' => $shipment->getAddress1(),
            'address2'=> $shipment->getAddress2(),
            'postcode' => $shipment->getPostCode(),
            'city' => $shipment->getCity(),
            'phone' => $shipment->getPhone(),
            'phoneMobile' => $shipment->getPhoneMobile(),
            'barcode' => $shipment->getBarcode()
        ];
    }
    private function createShipmentService(array $data, EntityManagerInterface $entityManager): void
    {
        $shipment = new Shipment();
        $shipment->setCountry($data['country']);
        $shipment->setCompany($data['company']);
        $shipment->setFirstName($data['firstName']);
        $shipment->setLastName($data['lastName']);
        $shipment->setAddress1($data['address1']);
        $shipment->setAddress2($data['address2']);
        $shipment->setPostcode($data['postcode']);
        $shipment->setCity($data['city']);
        $shipment->setPhone($data['phone']);
        $shipment->setPhoneMobile($data['phoneMobile']);
        $shipment->setBarcode(decbin(time()));

        $entityManager->persist($shipment);
        $entityManager->flush();
    }
    
}
