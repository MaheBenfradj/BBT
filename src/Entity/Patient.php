<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephine;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="patient")
     */
    private $devis;

    /**
     * @ORM\OneToMany(targetEntity=DemandeRDV::class, mappedBy="patient")
     */
    private $demandeRDVs;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->demandeRDVs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getTelephine(): ?string
    {
        return $this->telephine;
    }

    public function setTelephine(string $telephine): self
    {
        $this->telephine = $telephine;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setPatient($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getPatient() === $this) {
                $devi->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeRDV>
     */
    public function getDemandeRDVs(): Collection
    {
        return $this->demandeRDVs;
    }

    public function addDemandeRDV(DemandeRDV $demandeRDV): self
    {
        if (!$this->demandeRDVs->contains($demandeRDV)) {
            $this->demandeRDVs[] = $demandeRDV;
            $demandeRDV->setPatient($this);
        }

        return $this;
    }

    public function removeDemandeRDV(DemandeRDV $demandeRDV): self
    {
        if ($this->demandeRDVs->removeElement($demandeRDV)) {
            // set the owning side to null (unless already changed)
            if ($demandeRDV->getPatient() === $this) {
                $demandeRDV->setPatient(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string)$this->nom.'#'.$this->email.'#'.$this->telephine.'#'.$this->pays;

    }
}
