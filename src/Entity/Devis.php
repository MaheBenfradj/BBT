<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DevisRepository::class)
 */
class Devis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="devis")
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Intervention::class, inversedBy="devis")
     */
    private $operation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $AntecedentsMedicaux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $antecedentsChirurgicaux;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $traitementEnCours;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateIntervention;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getOperation(): ?Intervention
    {
        return $this->operation;
    }

    public function setOperation(?Intervention $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getAntecedentsMedicaux(): ?string
    {
        return $this->AntecedentsMedicaux;
    }

    public function setAntecedentsMedicaux(?string $AntecedentsMedicaux): self
    {
        $this->AntecedentsMedicaux = $AntecedentsMedicaux;

        return $this;
    }

    public function getAntecedentsChirurgicaux(): ?string
    {
        return $this->antecedentsChirurgicaux;
    }

    public function setAntecedentsChirurgicaux(?string $antecedentsChirurgicaux): self
    {
        $this->antecedentsChirurgicaux = $antecedentsChirurgicaux;

        return $this;
    }

    public function getTraitementEnCours(): ?string
    {
        return $this->traitementEnCours;
    }

    public function setTraitementEnCours(?string $traitementEnCours): self
    {
        $this->traitementEnCours = $traitementEnCours;

        return $this;
    }

    public function getDateIntervention(): ?\DateTimeInterface
    {
        return $this->dateIntervention;
    }

    public function setDateIntervention(?\DateTimeInterface $dateIntervention): self
    {
        $this->dateIntervention = $dateIntervention;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
