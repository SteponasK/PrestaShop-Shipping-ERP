<?php

namespace Invertus\Academy\ShipmentCreateService;

use Invertus\Academy\Entity\Shipment;
use Doctrine\ORM\EntityManagerInterface;
use Invertus\Academy\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
class ShipmentCreateService
{
    public function createShipment(array $data, EntityManagerInterface $entityManager) : void {
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
    public function isApiKeyValid(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return false;
        }
        return true;
    }
}