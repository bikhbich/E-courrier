<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Arrivee
 *
 * @ORM\Table(name="arrivee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArriveeRepository")
 */
class Arrivee
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
     * @ORM\Column(name="RefArrivee", type="string", length=255,  nullable=True)
     */
    private $refArrivee;

    /**
     * @var string
     *
     * @ORM\Column(name="RefCourrier", type="string", length=255 , nullable=True)
     */
    private $refCourrier;


    /**
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="Objet", type="text")
     */
    private $objet;

    /**
     * @var \Date
     *
     * @ORM\Column(name="DateCourrier", type="date")
     */
    private $dateCourrier;

    /**
     * @var \Date
     *
     * @ORM\Column(name="DateArrivee", type="date")
     */
    private $dateArrivee;

    /**
     * @var \Date
     *
     * @ORM\Column(name="DateCreation", type="date")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="Expediteurs", inversedBy="arrivee")
     * @ORM\JoinColumn(name="expediteur_id", referencedColumnName="id")
     */
    protected $expediteur;

    /**
     * @ORM\Column(type="string" ,  nullable=True)
     *
     * @Assert\NotBlank(message="Merci de sélectionner votre fichier.")
     * @Assert\File(mimeTypes={ "image/png","image/jpeg","image/jpg","image/gif","application/pdf","application/x-pdf","application/x-msword","application/vnd.ms-word","application/msword" })
     */
    protected $fichier;

    /**
     * @var integer
     *
     * @ORM\Column(name="Statut", type="integer")
     */
    protected $statut;

    /**
     * @ORM\ManyToOne(targetEntity="Departements", inversedBy="arrivee")
     * @ORM\JoinColumn(name="departement", referencedColumnName="id")
     */
    protected $departement;

    

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
     * Set refArrivee
     *
     * @param string $refArrivee
     *
     * @return Arrivee
     */
    public function setRefArrivee($refArrivee)
    {
        $this->refArrivee = $refArrivee;

        return $this;
    }

    /**
     * Get refArrivee
     *
     * @return string
     */
    public function getRefArrivee()
    {
        return $this->refArrivee;
    }

    /**
     * Set refCourrier
     *
     * @param string $refCourrier
     *
     * @return Arrivee
     */
    public function setRefCourrier($refCourrier)
    {
        $this->refCourrier = $refCourrier;

        return $this;
    }

    /**
     * Get refCourrier
     *
     * @return string
     */
    public function getRefCourrier()
    {
        return $this->refCourrier;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return Arrivee
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
     * Set nature
     *
     * @param string $nature
     *
     * @return Arrivee
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }


    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set dateCourrier
     *
     * @param \DateTime $dateCourrier
     *
     * @return Arrivee
     */
    public function setDateCourrier($dateCourrier)
    {
        $this->dateCourrier = $dateCourrier;

        return $this;
    }

    /**
     * Get dateCourrier
     *
     * @return \DateTime
     */
    public function getDateCourrier()
    {
        return $this->dateCourrier;
    }

    /**
     * Set dateArrivee
     *
     * @param \DateTime $dateArrivee
     *
     * @return Arrivee
     */
    public function setDateArrivee($dateArrivee)
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    /**
     * Get dateArrivee
     *
     * @return \DateTime
     */
    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Arrivee
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set expediteur
     *
     * @param \AppBundle\Entity\Expediteurs $expediteur
     *
     * @return Arrivee
     */
    public function setExpediteur(\AppBundle\Entity\Expediteurs $expediteur = null)
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    /**
     * Get expediteur
     *
     * @return \AppBundle\Entity\Expediteurs
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * Set objet
     *
     * @param string $objet
     *
     * @return Arrivee
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Set fichier
     *
     * @param string $fichier
     *
     * @return Arrivee
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Arrivee
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set action
     *
     * @param \AppBundle\Entity\Actions $action
     *
     * @return Arrivee
     */
    public function setAction(\AppBundle\Entity\Actions $action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \AppBundle\Entity\Actions
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departements $departement
     *
     * @return Arrivee
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
}
