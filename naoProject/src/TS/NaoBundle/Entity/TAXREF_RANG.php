<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAXREF_RANG
 *
 * @ORM\Table(name="nao_taxref_rang")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\TAXREF_RANGRepository")
 */
class TAXREF_RANG
{
    /**
     * @var string
     *
     * @ORM\Column(name="rang", type="string", length=4, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $rang;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * Set rang.
     *
     * @param string $rang
     *
     * @return TAXREF_RANG
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang.
     *
     * @return string
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return TAXREF_RANG
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
