<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationsRepository")
 */
class Notifications
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
     * @ORM\ManyToOne(targetEntity="Arrivee", inversedBy="notification")
     * @ORM\JoinColumn(name="arrivee", referencedColumnName="id")
     */
    protected $arrivee;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notification")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;


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
     * Set statut
     *
     * @param integer $statut
     *
     * @return Notifications
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set arrivee
     *
     * @param \AppBundle\Entity\Arrivee $arrivee
     *
     * @return Notifications
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
     * @return Notifications
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
