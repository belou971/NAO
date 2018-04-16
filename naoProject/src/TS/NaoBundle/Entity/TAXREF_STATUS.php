<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAXREF_STATUS
 *
 * @ORM\Table(name="nao_taxref_status")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\TAXREF_STATUSRepository")
 */
class TAXREF_STATUS
{
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return TAXREF_STATUS
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return TAXREF_STATUS
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
