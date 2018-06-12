<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TS\NaoBundle\Entity\Observation;
use TS\NaoBundle\Entity\TAXREF;

/**
 * Image
 *
 * @ORM\Table(name="nao_image")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


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

    /*
     * @var File
     *
     */
    private $file;
    private $tempFileName;
    private $targetDirectory = null;


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
     * @param TAXREF|null $specimen
     *
     * @return Image
     */
    public function setSpecimen(TAXREF $specimen = null)
    {
        $this->specimen = $specimen;

        return $this;
    }

    /**
     * Get specimen.
     *
     * @return TAXREF|null
     */
    public function getSpecimen()
    {
        return $this->specimen;
    }

    /**
     * Set observation.
     *
     * @param Observation $observation
     *
     * @return Image
     */
    public function setObservation(Observation $observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation.
     *
     * @return Observation
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }


    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        if (!is_null($this->url)) {
            $this->tempFileName = $this->url;
            $this->url = null;
            $this->alt =null;
        }
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if(is_null($this->file)) {
            return;
        }

        $this->url = $this->generateUniqueFileName().'.'.$this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
    }


    /**
     * Upload the user file into the target directory
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(is_null($this->file)) {
            return;
        }

        if(!is_null($this->tempFileName)){
            $oldFile = $this->getTargetDirectory().'/'.$this->tempFileName;

            if(file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        if(!is_null($this->url)) {
            $this->file->move($this->getTargetDirectory(), $this->url);
        }
    }


    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
        $this->tempFileName = $this->getTargetDirectory().'/'.$this->url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if(!is_null($this->tempFileName) && file_exists($this->tempFileName)) {
            unlink($this->tempFileName);
        }
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }



    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @param mixed $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}
