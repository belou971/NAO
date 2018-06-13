<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TS\NaoBundle\Enum\ProfilEnum;
use TS\NaoBundle\Enum\StateEnum;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Observation
 *
 * @ORM\Table(name="nao_observation")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\ObservationRepository")
 */
class Observation
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
     * @var \DateTime
     *
     * @ORM\Column(name="dt_creation", type="datetime")
     * @Assert\DateTime(message = "La date n'est pas valide")
     * @Assert\NotNull(message = "Indiquer quand à eu lieu l'observation")
     */
    private $dtCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_modification", type="datetime")
     */
    private $dtModification;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message ="Le titre de l'observation est absente", payload={"severity"="error"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="specimen", type="string", length=255)
     * @Assert\NotBlank(message ="Le nom de l'espèce est vide", payload={"severity"="error"})
     */
    private $specimen;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_specimen", type="integer")
     * @Assert\Range(
     *     min=1,
     *     minMessage="Au moins {{ limit }} oiseau est observé"
     * )
     */
    private $nbSpecimen;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\NotBlank(message =" La longitude est absente", payload={"severity"="error"})
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\NotBlank(message ="La latitude est absente", payload={"severity"="error"})
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="text")
     * @Assert\NotBlank(message ="La description est vide", payload={"severity"="error"})
     */
    private $remarks;

    /**
     * @var StateEnum
     *
     * @ORM\Column(type="StateType")
     */
    private $state;

    /**
     * @var Image
     *
     * @ORM\OneToMany(targetEntity="TS\NaoBundle\Entity\Image", mappedBy="observation", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $images;

    /**
     * @var Image
     *
     */
    private $image1;
    /**
     * @var Image
     *
     */
    private $image2;

    /**
     * @var Image
     *
     */
    private $image3;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\User", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var TAXREF
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF", cascade={"persist"})
     * @ORM\JoinColumn(name="taxref", referencedColumnName="cd_nom", unique=false, nullable=false)
     */
    private $taxref;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dtCreation.
     *
     * @param \DateTime $dtCreation
     *
     * @return Observation
     */
    public function setDtCreation($dtCreation)
    {
        $this->dtCreation = $dtCreation;

        $this->setDtModification($this->dtCreation);

        return $this;
    }

    /**
     * Get dtCreation.
     *
     * @return \DateTime
     */
    public function getDtCreation()
    {
        return $this->dtCreation;
    }

    /**
     * Set dtModification.
     *
     * @param \DateTime $dtModification
     *
     * @return Observation
     */
    public function setDtModification($dtModification)
    {
        $this->dtModification = $dtModification;

        return $this;
    }

    /**
     * Get dtModification.
     *
     * @return \DateTime
     */
    public function getDtModification()
    {
        return $this->dtModification;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Observation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set specimen.
     *
     * @param string $specimen
     *
     * @return Observation
     */
    public function setSpecimen($specimen)
    {
        $this->specimen = $specimen;

        return $this;
    }

    /**
     * Get specimen.
     *
     * @return string
     */
    public function getSpecimen()
    {
        return $this->specimen;
    }

    /**
     * Set nbSpecimen.
     *
     * @param int $nbSpecimen
     *
     * @return Observation
     */
    public function setNbSpecimen($nbSpecimen)
    {
        $this->nbSpecimen = $nbSpecimen;

        return $this;
    }

    /**
     * Get nbSpecimen.
     *
     * @return int
     */
    public function getNbSpecimen()
    {
        return $this->nbSpecimen;
    }

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set remarks.
     *
     * @param string $remarks
     *
     * @return Observation
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks.
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set state.
     *
     * @param StateType $state
     *
     * @return Observation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return StateType
     */
    public function getState()
    {
        return $this->state;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image.
     *
     * @param \TS\NaoBundle\Entity\Image $image
     *
     * @return Observation
     */
    public function addImage(\TS\NaoBundle\Entity\Image $image)
    {
        if (!is_null($image) && !is_null($image->getFile())) {
            $this->images[] = $image;
        }

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \TS\NaoBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\TS\NaoBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set user.
     *
     * @param \TS\NaoBundle\Entity\User $user
     *
     * @return Observation
     */
    public function setUser(\TS\NaoBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \TS\NaoBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set taxref.
     *
     * @param \TS\NaoBundle\Entity\TAXREF $taxref
     *
     * @return Observation
     */
    public function setTaxref(\TS\NaoBundle\Entity\TAXREF $taxref)
    {
        $this->taxref = $taxref;

        return $this;
    }

    /**
     * Get taxref.
     *
     * @return \TS\NaoBundle\Entity\TAXREF
     */
    public function getTaxref()
    {
        return $this->taxref;
    }


    /**
     * @return Image
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param Image $image1
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
        if(!is_null($this->image1)) {
            $this->addImage($this->image1);
        }
    }

    /**
     * @return Image
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param Image $image2
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
        if(!is_null($this->image2)) {
            $this->addImage($this->image2);
        }
    }

    /**
     * @return Image
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param Image $image3
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;
        if(!is_null($this->image3)) {
            $this->addImage($this->image3);
        }
    }


    /**
     * @param ExecutionContextInterface $context
     * @Assert\IsTrue(message = "La date d'observation postérieure à aujourd'hui")
     * @return bool
     */
    public function isBeforeTomorrow() {

        $tomorrow = date_modify(new \DateTime(), '+1 day');

        return ($this->dtCreation < $tomorrow);
    }

    public function updateStatusFromUserRole() {
        if(is_null($this->user)) {
            return;
        }

        $roles  = $this->user->getRoles();
        if(in_array(ProfilEnum::ADMIN, $roles)){
            $this->setState(StateEnum::VALIDATE);
        } else if(in_array(ProfilEnum::NATURALIST, $roles)) {
            $this->setState(StateEnum::VALIDATE);
        } else {
            $this->setState(StateEnum::SUBMIT);
        }
    }
}
