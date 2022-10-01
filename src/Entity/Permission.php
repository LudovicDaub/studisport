<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isNewsletter = null;

    #[ORM\Column]
    private ?bool $isVenteBoisson = null;

    #[ORM\Column]
    private ?bool $isOffreSac = null;

    #[ORM\Column]
    private ?bool $isBadgePerso = null;

    #[ORM\Column]
    private ?bool $isVenteMerch = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsNewsletter(): ?bool
    {
        return $this->isNewsletter;
    }

    public function setIsNewsletter(bool $isNewsletter): self
    {
        $this->isNewsletter = $isNewsletter;

        return $this;
    }

    public function isIsVenteBoisson(): ?bool
    {
        return $this->isVenteBoisson;
    }

    public function setIsVenteBoisson(bool $isVenteBoisson): self
    {
        $this->isVenteBoisson = $isVenteBoisson;

        return $this;
    }

    public function isIsOffreSac(): ?bool
    {
        return $this->isOffreSac;
    }

    public function setIsOffreSac(bool $isOffreSac): self
    {
        $this->isOffreSac = $isOffreSac;

        return $this;
    }

    public function isIsBadgePerso(): ?bool
    {
        return $this->isBadgePerso;
    }

    public function setIsBadgePerso(bool $isBadgePerso): self
    {
        $this->isBadgePerso = $isBadgePerso;

        return $this;
    }

    public function isIsVenteMerch(): ?bool
    {
        return $this->isVenteMerch;
    }

    public function setIsVenteMerch(bool $isVenteMerch): self
    {
        $this->isVenteMerch = $isVenteMerch;

        return $this;
    }
}
