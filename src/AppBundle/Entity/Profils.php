<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profils
 *
 * @ORM\Table(name="profils")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfilsRepository")
 */
class Profils
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Profil", type="string", length=255)
     */
    private $profil;

    /**
     * @ORM\ManyToOne(targetEntity="Departements", inversedBy="Profils")
     * @ORM\JoinColumn(name="Departement", referencedColumnName="id")
     */
    protected $Departement;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set profil
     *
     * @param string $profil
     *
     * @return Profils
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return string
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departements $departement
     *
     * @return Profils
     */
    public function setDepartement(\AppBundle\Entity\Departements $departement = null)
    {
        $this->Departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \AppBundle\Entity\Departements
     */
    public function getDepartement()
    {
        return $this->Departement;
    }
}
