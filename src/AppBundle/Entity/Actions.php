<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actions
 *
 * @ORM\Table(name="actions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActionsRepository")
 */
class Actions
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
     * @ORM\Column(name="value", type="string", length=255 ,  nullable=True)
     */
    private $value;

    
    /**
     * @ORM\ManyToOne(targetEntity="Arrivee", inversedBy="action")
     * @ORM\JoinColumn(name="arrivee", referencedColumnName="id")
     */
    protected $arrivee;

    /**
     * @ORM\ManyToOne(targetEntity="Controles", inversedBy="action")
     * @ORM\JoinColumn(name="controle", referencedColumnName="id")
     */
    protected $controle;


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
     * Set value
     *
     * @param string $value
     *
     * @return Actions
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set controle
     *
     * @param \AppBundle\Entity\Controles $controle
     *
     * @return Actions
     */
    public function setControle(\AppBundle\Entity\Controles $controle = null)
    {
        $this->controle = $controle;

        return $this;
    }

    /**
     * Get controle
     *
     * @return \AppBundle\Entity\Controles
     */
    public function getControle()
    {
        return $this->controle;
    }

    /**
     * Set arrivee
     *
     * @param \AppBundle\Entity\Arrivee $arrivee
     *
     * @return Actions
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
}
