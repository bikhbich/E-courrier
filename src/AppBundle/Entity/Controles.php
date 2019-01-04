<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Controles
 *
 * @ORM\Table(name="controles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ControlesRepository")
 */
class Controles
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="pave", type="string", length=50)
     */
    private $pave;


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
     * Set label
     *
     * @param string $label
     *
     * @return Controles
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Controles
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set pave
     *
     * @param string $pave
     *
     * @return Controles
     */
    public function setPave($pave)
    {
        $this->pave = $pave;

        return $this;
    }

    /**
     * Get pave
     *
     * @return string
     */
    public function getPave()
    {
        return $this->pave;
    }
}
