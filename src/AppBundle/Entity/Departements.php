<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departements
 *
 * @ORM\Table(name="departements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepartementsRepository")
 */
class Departements
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
     * @ORM\Column(name="departement", type="string", length=255)
     */
    private $departement;

    /**
     * @var integer
     *
     * @ORM\Column(name="Type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="departement")
     * @ORM\JoinColumn(name="Chef", referencedColumnName="id")
     */
    protected $chef;


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
     * Set departement
     *
     * @param string $departement
     *
     * @return Departements
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Departements
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Departements
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set chef
     *
     * @param \AppBundle\Entity\User $chef
     *
     * @return Departements
     */
    public function setChef(\AppBundle\Entity\User $chef = null)
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * Get chef
     *
     * @return \AppBundle\Entity\User
     */
    public function getChef()
    {
        return $this->chef;
    }
}
