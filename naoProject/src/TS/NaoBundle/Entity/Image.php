<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="nao_image")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var TAXREF
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF", cascade={"persist"})
     * @ORM\JoinColumn(name="specimen", referencedColumnName="cd_nom", unique=false)
     */
    private $specimen;

    /**
     * @var Observation
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\Observation", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $observation;


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
     * Set url.
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set specimen.
     *
     * @param \TS\NaoBundle\Entity\TAXREF|null $specimen
     *
     * @return Image
     */
    public function setSpecimen(\TS\NaoBundle\Entity\TAXREF $specimen = null)
    {
        $this->specimen = $specimen;

        return $this;
    }

    /**
     * Get specimen.
     *
     * @return \TS\NaoBundle\Entity\TAXREF|null
     */
    public function getSpecimen()
    {
        return $this->specimen;
    }

    /**
     * Set observation.
     *
     * @param \TS\NaoBundle\Entity\Observation $observation
     *
     * @return Image
     */
    public function setObservation(\TS\NaoBundle\Entity\Observation $observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation.
     *
     * @return \TS\NaoBundle\Entity\Observation
     */
    public function getObservation()
    {
        return $this->observation;
    }
}
