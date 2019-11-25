<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursRepository")
 */
class Cours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $datepub;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2,max=255)
     * @Assert\Valid()
     */
    private $lien;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2,max=30, minMessage="Le titre doit faire au moins {{ limit }} caractères.")
     * @Assert\NotBlank()
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotBlank()
     */
    
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="cours")
     */
    private $niveau;

    public function getNiveau()
    {
        return $this->niveau;
    }

    public function setNiveau($niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Filiere", inversedBy="cours")
     */
    private $filiere;

    public function getFiliere()
    {
        return $this->filiere;
    }

    public function setFiliere($filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getTitre(){

        return $this->titre;
    }

    public function setTitre($titre){
        $this->titre = $titre;
    }

    public function getDescription(){

        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatepub(): ?\DateTimeInterface
    {
        return $this->datepub;
    }

    public function setDatepub(\DateTimeInterface $datepub): self
    {
        $this->datepub = $datepub;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }
}
