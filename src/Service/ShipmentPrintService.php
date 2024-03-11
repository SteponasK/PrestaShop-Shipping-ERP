<?php

namespace Invertus\Academy\ShipmentPrintService;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Invertus\Academy\Entity\Shipment;

class ShipmentPrintService
{
    public function isApiKeyValid(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return false;
        }
        return true;
    }
    public function getShipment(EntityManagerInterface $entityManager, int $id)
    {
        return  $entityManager->getRepository(Shipment::class)->find($id);
    }
}