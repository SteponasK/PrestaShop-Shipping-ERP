<?php
// Shipment endpointas
namespace Invertus\Academy\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
      
        //authorisation check
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        
        if ($_ENV['API_KEY'] !== $token) {
            return new JsonResponse(['error' => 'Invalid API key'], Response::HTTP_UNAUTHORIZED);
        }
        // in postman use body window to provide data in .json format
        // entity manager is mainly used only for persist and flush functions

        $data = json_decode($request->getContent(), true); // decodes data into an array ($data)


        // other logic
        return new Response("foo", 333);
    }
    // print shipment function needed
}
    