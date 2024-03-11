<?php

namespace Invertus\Academy\ShipmentCreateService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use Invertus\Academy\Entity\Shipment;

class ShipmentCreateService
{
    public function isApiKeyValid(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token)
        {
            return false;
        }
        return true;
    }

    public function getData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    public function createShipment(array $data, EntityManagerInterface $entityManager) : void
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