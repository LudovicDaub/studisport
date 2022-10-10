<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Partner::class, mappedBy: 'permissions')]
    private Collection $partners;

    #[ORM\ManyToMany(targetEntity: Structure::class, mappedBy: 'permissions')]
    private Collection $structures;

    public function __construct()
    {
        $this->partners = new ArrayCollection();
        $this->structures = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Partner>
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners->add($partner);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        $this->partners->removeElement($partner);

        return $this;
    }

    /**
     * @return Collection<int, Structure>
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures->add($structure);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        $this->structures->removeElement($structure);

        return $this;
    }
}
