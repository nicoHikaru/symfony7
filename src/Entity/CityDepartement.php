<?php

namespace App\Entity;

use App\Repository\CityDepartementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityDepartementRepository::class)]
class CityDepartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cityId = null;

    #[ORM\Column]
    private ?int $departementId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function setCityId(int $cityId): static
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getDepartementId(): ?int
    {
        return $this->departementId;
    }

    public function setDepartementId(int $departementId): static
    {
        $this->departementId = $departementId;

        return $this;
    }
}
