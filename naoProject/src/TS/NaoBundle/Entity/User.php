<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TS\NaoBundle\Enum\ProfilEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * User
 *
 * @ORM\Table(name="nao_user")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cette adresse e-mail est déjà utilisée.")
 * @UniqueEntity(fields="pseudo", message="Ce pseudo existe déjà.")
 */
class User
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le nom doit faire au moins {{limit}} caractères.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le prénom doit faire au moins {{limit}} caractères.")
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pseudo", type="string", length=255, nullable=true)
     * @Assert\Length(min=2, minMessage="Le pseudo doit faire au moins {{limit}} caractères.")
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(message="Veuillez entrer une adresse e-mail correcte.", checkMX=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $pwd;

    /**
     * @var ProfilEnum
     *
     * @ORM\Column(type="ProfilType")
     */
    private $profil;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=255, unique=true, nullable=true)
     */
    private $grade;

    /**
     * @var Observation
     *
     * @ORM\OneToMany(targetEntity="TS\NaoBundle\Entity\Observation", mappedBy="user", cascade={"remove"})
     */
    private $observations;


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
     * Set name.
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname.
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set pseudo.
     *
     * @param string|null $pseudo
     *
     * @return User
     */
    public function setPseudo($pseudo = null)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo.
     *
     * @return string|null
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pwd.
     *
     * @param string $pwd
     *
     * @return User
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }

    /**
     * Get pwd.
     *
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @Assert\Callback
     */
    public function isPwdValid(ExecutionContextInterface $context)
    {
        $pwd = strtolower($this->getPwd());

        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#', $this->getPwd())) {
            $context->buildViolation('Votre mot de passe doit faire au minimum 6 caractères et contenir au moins 1 lettre min, 1 lettre maj et 1 chiffre.')->atPath('pwd')->addViolation();
        }
        elseif ($pwd == strtolower($this->getEmail()) || $pwd == strtolower($this->getName()) || $pwd == strtolower($this->getSurname()) || $pwd == strtolower($this->getPseudo())) {
            $context->buildViolation('Votre mot de passe doit être différent de votre nom, prénom, adresse e-mail ou de votre pseudo.')->atPath('pwd')->addViolation();
        }
    }


    /**
     * Set profil.
     *
     * @param ProfilType $profil
     *
     * @return User
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil.
     *
     * @return ProfilType
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set grade.
     *
     * @param string|null $grade
     *
     * @return User
     */
    public function setGrade($grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade.
     *
     * @return string|null
     */
    public function getGrade()
    {
        return $this->grade;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add observation.
     *
     * @param \TS\NaoBundle\Entity\Observation $observation
     *
     * @return User
     */
    public function addObservation(\TS\NaoBundle\Entity\Observation $observation)
    {
        $this->observations[] = $observation;

        return $this;
    }

    /**
     * Remove observation.
     *
     * @param \TS\NaoBundle\Entity\Observation $observation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeObservation(\TS\NaoBundle\Entity\Observation $observation)
    {
        return $this->observations->removeElement($observation);
    }

    /**
     * Get observations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }
}
