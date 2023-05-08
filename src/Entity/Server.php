<?php

namespace App\Entity;

use App\Enum\Currency;
use App\Repository\ServerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column]
    private ?int $ramSize = null;

    #[ORM\Column(length: 2)]
    private ?string $ramSizeType = null;

    #[ORM\Column(length: 7)]
    private ?string $ramType = null;

    #[ORM\Column]
    private ?int $hddCount = null;

    #[ORM\Column]
    private ?int $hddSize = null;

    #[ORM\Column]
    private ?int $hddTotalSize = null;

    #[ORM\Column(length: 2)]
    private ?string $hddSizeType = null;

    #[ORM\Column(length: 10)]
    private ?string $hddType = null;

    #[ORM\Column(length: 65)]
    private ?string $actualRamSize = null;

    #[ORM\Column(length: 65)]
    private ?string $actualHddSize = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 15)]
    private ?string $priceCurrency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRamSize(): ?int
    {
        return $this->ramSize;
    }

    public function setRamSize(int $ramSize): self
    {
        $this->ramSize = $ramSize;

        return $this;
    }

    public function getRamSizeType(): ?string
    {
        return $this->ramSizeType;
    }

    public function setRamSizeType(string $ramSizeType): self
    {
        $this->ramSizeType = $ramSizeType;

        return $this;
    }

    public function getRamType(): ?string
    {
        return $this->ramType;
    }

    public function setRamType(string $ramType): self
    {
        $this->ramType = $ramType;

        return $this;
    }

    public function getHddCount(): ?int
    {
        return $this->hddCount;
    }

    public function setHddCount(int $hddCount): self
    {
        $this->hddCount = $hddCount;

        return $this;
    }

    public function getHddSize(): ?int
    {
        return $this->hddSize;
    }

    public function setHddSize(int $hddSize): self
    {
        $this->hddSize = $hddSize;

        return $this;
    }

    public function getHddSizeType(): ?string
    {
        return $this->hddSizeType;
    }

    public function setHddSizeType(string $hddSizeType): self
    {
        $this->hddSizeType = $hddSizeType;

        return $this;
    }

    public function getHddType(): ?string
    {
        return $this->hddType;
    }

    public function setHddType(string $hddType): self
    {
        $this->hddType = $hddType;

        return $this;
    }

    public function getActualRamSize(): ?int
    {
        return $this->actualRamSize;
    }

    public function setActualRamSize(int $actualRamSize): self
    {
        $this->actualRamSize = $actualRamSize;

        return $this;
    }

    public function getActualHddSize(): ?int
    {
        return $this->actualHddSize;
    }

    public function setActualHddSize(int $actualHddSize): self
    {
        $this->actualHddSize = $actualHddSize;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setPriceCurrency(string $priceCurrency): self
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHddTotalSize(): ?int
    {
        return $this->hddTotalSize;
    }

    /**
     * @param int|null $hddTotalSize
     */
    public function setHddTotalSize(?int $hddTotalSize): void
    {
        $this->hddTotalSize = $hddTotalSize;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'model' => $this->getModel(),
            'price' => Currency::fromName($this->getPriceCurrency())->value . $this->getPrice(),
        ];
    }
}
