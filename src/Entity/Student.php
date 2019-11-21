<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieunaissance;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $dateinscription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="student")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Niveau", inversedBy="student")
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Filiere", inversedBy="student")
     */
    private $filiere;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Universite", inversedBy="student")
     */
    private $universite;


    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getDatenaissance(){
        return $this->datenaissance;
    }
    public function setDatenaissance($datenaissance){
         $this->datenaissance = $datenaissance;
    }


    public function getLieunaissance(){
        return $this->lieunaissance;
    }
    public function setLieunaissance($lieunaissance){
         $this->lieunaissance = $lieunaissance;
    }

    public function getMatricule(){
        return $this->matricule;
    }
    public function setMatricule($matricule){
         $this->matricule = $matricule;
    }

    public function getNiveau(){
        return $this->niveau;
    }
    public function setNiveau($niveau): self{
         $this->niveau = $niveau;
         return $this;
    }

    public function getFiliere(){
        return $this->filiere;
    }
    public function setFiliere($filiere) : self {
         $this->filiere = $filiere;
         return $this;
    }

    public function getUniversite(){
        return $this->universite;
    }
    public function setUniversite($universite) : self{
         $this->universite = $universite;
         return $this;
    }

    public function setDateinscription($dateinscription){

        $this->dateinscription = $dateinscription;
    }

    public function getDateinscription(){
        return $this->dateinscription;
    }



    public function getId(): ?int
    {
        return $this->id;
    }


}
