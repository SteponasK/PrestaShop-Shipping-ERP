<?php

namespace Invertus\Academy\ShipmentCreateService;

use Doctrine\ORM\EntityManagerInterface;

use Invertus\Academy\Entity\Shipment;

class ShipmentCreateService
{
    public function createShipment(array $data, EntityManagerInterface $entityManager) : int
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

        return $shipment->getId();
    }
}