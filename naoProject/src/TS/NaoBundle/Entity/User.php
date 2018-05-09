<?php
// src/TS/NaoBundle/Entity/User.php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TS\NaoBundle\Enum\ProfilEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Asset\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="nao_user")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cette adresse e-mail est déjà utilisée.")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur existe déjà.")
 */
class User implements UserInterface
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
     * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage="Le nom doit faire au moins {{ limit }} caractères.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage="Le prénom doit faire au moins {{ limit }} caractères.")
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\Length(min=2, minMessage="Le nom d'utilisateur doit faire au moins {{ limit }} caractères.")
     */
    private $username;

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
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Regex(pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#", message="Votre mot de passe doit faire au minimum 6 caractères et contenir au moins 1 lettre min, 1 lettre maj et 1 chiffre.")
     */
    private $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @ORM\Column(name="roles", type="array")
     * @Assert\NotBlank(message="Aucun type de profil n'a été affilié à ce compte.")
     */
    private $roles = array();

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
     * @var string
     *
     * @ORM\Column(name="recovery_token", type="string", length=255, nullable=true)
     */
    private $recovery_token;


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
     * Set username.
     *
     * @param string|null $username
     *
     * @return User
     */
    public function setUsername($username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
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
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        if(!in_array($roles, ProfilEnum::getValues())) {
            throw new InvalidArgumentException('Ce type de profil est inconnu.');
        }
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
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
        $this->roles = ProfilEnum::BIRD_FANCIER;
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

    public function eraseCredentials()
    {

    }

    /**
     * Set recoveryToken.
     *
     * @param string|null $recoveryToken
     *
     * @return User
     */
    public function setRecoveryToken($recoveryToken = null)
    {
        $this->recovery_token = $recoveryToken;

        return $this;
    }

    /**
     * Get recoveryToken.
     *
     * @return string|null
     */
    public function getRecoveryToken()
    {
        return $this->recovery_token;
    }
}
