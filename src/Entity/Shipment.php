<?php

namespace Invertus\Academy\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Invertus\Academy\Repository\LabelRepository;

#[ORM\Entity(repositoryClass: LabelRepository::class)]
class Shipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $senderAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $barcode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSenderAdress(): ?string
    {
        return $this->senderAddress;
    }

    public function setSenderAdress(string $senderAddress): static
    {
        $this->senderAddress = $senderAddress;

        return $this;
    }

    public function getDeliveryAdress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAdress(string $deliveryAdress): static
    {
        $this->deliveryAddress = $deliveryAdress;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }
}
