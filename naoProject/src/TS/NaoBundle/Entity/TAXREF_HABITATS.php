<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAXREF_HABITATS
 *
 * @ORM\Table(name="nao_taxref_habitats")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\TAXREF_HABITATSRepository")
 */
class TAXREF_HABITATS
{
    /**
     * @var int
     *
     * @ORM\Column(name="habitat", type="smallint", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $habitat;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * Set habitat.
     *
     * @param int $habitat
     *
     * @return TAXREF_HABITATS
     */
    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * Get habitat.
     *
     * @return int
     */
    public function getHabitat()
    {
        return $this->habitat;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return TAXREF_HABITATS
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
