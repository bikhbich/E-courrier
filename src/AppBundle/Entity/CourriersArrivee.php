<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourriersArrivee
 *
 * @ORM\Table(name="courriers_arrivee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourriersArriveeRepository")
 */
class CourriersArrivee
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
     * @ORM\ManyToOne(targetEntity="Arrivee", inversedBy="courrierArrivee")
     * @ORM\JoinColumn(name="arrivee", referencedColumnName="id")
     */
    protected $arrivee;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courrierArrivee")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;


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
     * Set arrivee
     *
     * @param \AppBundle\Entity\Arrivee $arrivee
     *
     * @return CourriersArrivee
     */
    public function setArrivee(\AppBundle\Entity\Arrivee $arrivee = null)
    {
        $this->arrivee = $arrivee;

        return $this;
    }

    /**
     * Get arrivee
     *
     * @return \AppBundle\Entity\Arrivee
     */
    public function getArrivee()
    {
        return $this->arrivee;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return CourriersArrivee
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
