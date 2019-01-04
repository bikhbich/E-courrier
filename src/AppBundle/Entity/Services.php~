<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Services
 *
 * @ORM\Table(name="services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicesRepository")
 */
class Services
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
     * @ORM\Column(name="service", type="string", length=255)
     */
    private $service;


    /**
     * @ORM\ManyToOne(targetEntity="Departements", inversedBy="service")
     * @ORM\JoinColumn(name="departement", referencedColumnName="id")
     */
    protected $departement;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="user" )
     * @ORM\JoinColumn(name="chef", referencedColumnName="id" , nullable=True)
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
     * Set service
     *
     * @param string $service
     *
     * @return Services
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departements $departement
     *
     * @return Services
     */
    public function setDepartement(\AppBundle\Entity\Departements $departement = null)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \AppBundle\Entity\Departements
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set chef
     *
     * @param \AppBundle\Entity\User $chef
     *
     * @return Services
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
