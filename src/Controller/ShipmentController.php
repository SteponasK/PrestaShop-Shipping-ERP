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

        $shipment = new Shipment();
        $shipment->setFullName($data['fullName']);
        $shipment->setPhoneNumber($data['phoneNumber']);
        $shipment->setSenderAddress($data['senderAddress']);
        $shipment->setDeliveryAddress($data['deliveryAddress']);
        $shipment->setBarcode(decbin(time()));
        
        $entityManager->persist($shipment);
        $entityManager->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/shipment/print/{id}', name: 'app_print_shipment', methods: ['GET'])]
    public function print(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            // return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
 
        $shipment = $entityManager->getRepository(Shipment::class)->find($id);
        var_dump($shipment);
        if(!$shipment){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        
        return new Response();
    }
}
