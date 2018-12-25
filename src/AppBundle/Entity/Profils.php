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
     * @var integer
     *
     * @ORM\Column(name="Niveau", type="integer")
     */
    protected $niveau;


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
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return Profils
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return integer
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
}
