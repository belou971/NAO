<?php

namespace TS\NaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAXREF
 *
 * @ORM\Table(name="nao_taxref")
 * @ORM\Entity(repositoryClass="TS\NaoBundle\Repository\TAXREFRepository")
 */
class TAXREF
{
    /**
     * @var int
     *
     * @ORM\Column(name="cd_nom", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $cdNom;

    /**
     * @var int
     *
     * @ORM\Column(name="cd_sup", type="bigint")
     */
    private $cdSup;

    /**
     * @var int
     *
     * @ORM\Column(name="cd_ref", type="bigint")
     */
    private $cdRef;


    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="text")
     */
    private $lbNom;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_auteur", type="text")
     */
    private $lbAuteur;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_complet", type="text")
     */
    private $nomComplet;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_valide", type="text")
     */
    private $nomValide;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_vern", type="text")
     */
    private $nomVern;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_vern_eng", type="string", length=255)
     */
    private $nomVernEng;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_RANG
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_RANG", cascade={"persist"})
     * @ORM\JoinColumn(name="rang", referencedColumnName="rang", unique=false)
     */
    private $rang;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_HABITATS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_HABITATS", cascade={"persist"})
     * @ORM\JoinColumn(name="habitat", referencedColumnName="habitat", unique=false)
     */
    private $habitat;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="fr", referencedColumnName="status", unique=false, nullable=true)
     */
    private $fr;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="gf", referencedColumnName="status", unique=false, nullable=true)
     */
    private $gf;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="mar", referencedColumnName="status", unique=false, nullable=true)
     */
    private $mar;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="gua", referencedColumnName="status", unique=false, nullable=true)
     */
    private $gua;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="sm", referencedColumnName="status", unique=false, nullable=true)
     */
    private $sm;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="sb", referencedColumnName="status", unique=false, nullable=true)
     */
    private $sb;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="spm", unique=false, referencedColumnName="status", nullable=true)
     */
    private $spm;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="may", unique=false, referencedColumnName="status", nullable=true)
     */
    private $may;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="epa", unique=false, referencedColumnName="status", nullable=true)
     */
    private $epa;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="reu", unique=false, referencedColumnName="status", nullable=true)
     */
    private $reu;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="sa", unique=false, referencedColumnName="status", nullable=true)
     */
    private $sa;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="ta", unique=false, referencedColumnName="status", nullable=true)
     */
    private $ta;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="nc", unique=false, referencedColumnName="status", nullable=true)
     */
    private $nc;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="wf", unique=false, referencedColumnName="status", nullable=true)
     */
    private $wf;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="pf", unique=false, referencedColumnName="status", nullable=true)
     */
    private $pf;

    /**
     * @var \TS\NaoBundle\Entity\TAXREF_STATUS
     *
     * @ORM\ManyToOne(targetEntity="TS\NaoBundle\Entity\TAXREF_STATUS", cascade={"persist"})
     * @ORM\JoinColumn(name="cli", unique=false, referencedColumnName="status", nullable=true)
     */
    private $cli;

    /**
     * @var string
     *
     * @ORM\Column(name="regne", type="text")
     */
    private $regne;

    /**
     * @var string
     *
     * @ORM\Column(name="phylum", type="text")
     */
    private $phylum;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="text")
     */
    private $classe;

    /**
     * @var string
     *
     * @ORM\Column(name="ordre", type="text")
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="famille", type="text")
     */
    private $famille;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;


    /**
     * Set cdNom.
     *
     * @param int $cdNom
     *
     * @return TAXREF
     */
    public function setCdNom($cdNom)
    {
        $this->cdNom = $cdNom;

        return $this;
    }

    /**
     * Get cdNom.
     *
     * @return int
     */
    public function getCdNom()
    {
        return $this->cdNom;
    }

    /**
     * Set cdSup.
     *
     * @param int $cdSup
     *
     * @return TAXREF
     */
    public function setCdSup($cdSup)
    {
        $this->cdSup = $cdSup;

        return $this;
    }

    /**
     * Get cdSup.
     *
     * @return int
     */
    public function getCdSup()
    {
        return $this->cdSup;
    }

    /**
     * Set cdRef.
     *
     * @param int $cdRef
     *
     * @return TAXREF
     */
    public function setCdRef($cdRef)
    {
        $this->cdRef = $cdRef;

        return $this;
    }

    /**
     * Get cdRef.
     *
     * @return int
     */
    public function getCdRef()
    {
        return $this->cdRef;
    }

    /**
     * Set lbNom.
     *
     * @param string $lbNom
     *
     * @return TAXREF
     */
    public function setLbNom($lbNom)
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    /**
     * Get lbNom.
     *
     * @return string
     */
    public function getLbNom()
    {
        return $this->lbNom;
    }

    /**
     * Set lbAuteur.
     *
     * @param string $lbAuteur
     *
     * @return TAXREF
     */
    public function setLbAuteur($lbAuteur)
    {
        $this->lbAuteur = $lbAuteur;

        return $this;
    }

    /**
     * Get lbAuteur.
     *
     * @return string
     */
    public function getLbAuteur()
    {
        return $this->lbAuteur;
    }

    /**
     * Set nomComplet.
     *
     * @param string $nomComplet
     *
     * @return TAXREF
     */
    public function setNomComplet($nomComplet)
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    /**
     * Get nomComplet.
     *
     * @return string
     */
    public function getNomComplet()
    {
        return $this->nomComplet;
    }

    /**
     * Set nomValide.
     *
     * @param string $nomValide
     *
     * @return TAXREF
     */
    public function setNomValide($nomValide)
    {
        $this->nomValide = $nomValide;

        return $this;
    }

    /**
     * Get nomValide.
     *
     * @return string
     */
    public function getNomValide()
    {
        return $this->nomValide;
    }

    /**
     * Set nomVern.
     *
     * @param string $nomVern
     *
     * @return TAXREF
     */
    public function setNomVern($nomVern)
    {
        $this->nomVern = $nomVern;

        return $this;
    }

    /**
     * Get nomVern.
     *
     * @return string
     */
    public function getNomVern()
    {
        return $this->nomVern;
    }

    /**
     * Set nomVernEng.
     *
     * @param string $nomVernEng
     *
     * @return TAXREF
     */
    public function setNomVernEng($nomVernEng)
    {
        $this->nomVernEng = $nomVernEng;

        return $this;
    }

    /**
     * Get nomVernEng.
     *
     * @return string
     */
    public function getNomVernEng()
    {
        return $this->nomVernEng;
    }

    /**
     * Set rang.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_RANG|null $rang
     *
     * @return TAXREF
     */
    public function setRang(\TS\NaoBundle\Entity\TAXREF_RANG $rang = null)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_RANG|null
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set habitat.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_HABITATS|null $habitat
     *
     * @return TAXREF
     */
    public function setHabitat(\TS\NaoBundle\Entity\TAXREF_HABITATS $habitat = null)
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * Get habitat.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_HABITATS|null
     */
    public function getHabitat()
    {
        return $this->habitat;
    }

    /**
     * Set fr.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $fr
     *
     * @return TAXREF
     */
    public function setFr(\TS\NaoBundle\Entity\TAXREF_STATUS $fr = null)
    {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get fr.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set gf.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $gf
     *
     * @return TAXREF
     */
    public function setGf(\TS\NaoBundle\Entity\TAXREF_STATUS $gf = null)
    {
        $this->gf = $gf;

        return $this;
    }

    /**
     * Get gf.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getGf()
    {
        return $this->gf;
    }

    /**
     * Set mar.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $mar
     *
     * @return TAXREF
     */
    public function setMar(\TS\NaoBundle\Entity\TAXREF_STATUS $mar = null)
    {
        $this->mar = $mar;

        return $this;
    }

    /**
     * Get mar.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getMar()
    {
        return $this->mar;
    }

    /**
     * Set gua.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $gua
     *
     * @return TAXREF
     */
    public function setGua(\TS\NaoBundle\Entity\TAXREF_STATUS $gua = null)
    {
        $this->gua = $gua;

        return $this;
    }

    /**
     * Get gua.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getGua()
    {
        return $this->gua;
    }

    /**
     * Set sm.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $sm
     *
     * @return TAXREF
     */
    public function setSm(\TS\NaoBundle\Entity\TAXREF_STATUS $sm = null)
    {
        $this->sm = $sm;

        return $this;
    }

    /**
     * Get sm.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getSm()
    {
        return $this->sm;
    }

    /**
     * Set sb.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $sb
     *
     * @return TAXREF
     */
    public function setSb(\TS\NaoBundle\Entity\TAXREF_STATUS $sb = null)
    {
        $this->sb = $sb;

        return $this;
    }

    /**
     * Get sb.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getSb()
    {
        return $this->sb;
    }

    /**
     * Set spm.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $spm
     *
     * @return TAXREF
     */
    public function setSpm(\TS\NaoBundle\Entity\TAXREF_STATUS $spm = null)
    {
        $this->spm = $spm;

        return $this;
    }

    /**
     * Get spm.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getSpm()
    {
        return $this->spm;
    }

    /**
     * Set may.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $may
     *
     * @return TAXREF
     */
    public function setMay(\TS\NaoBundle\Entity\TAXREF_STATUS $may = null)
    {
        $this->may = $may;

        return $this;
    }

    /**
     * Get may.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getMay()
    {
        return $this->may;
    }

    /**
     * Set epa.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $epa
     *
     * @return TAXREF
     */
    public function setEpa(\TS\NaoBundle\Entity\TAXREF_STATUS $epa = null)
    {
        $this->epa = $epa;

        return $this;
    }

    /**
     * Get epa.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getEpa()
    {
        return $this->epa;
    }

    /**
     * Set reu.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $reu
     *
     * @return TAXREF
     */
    public function setReu(\TS\NaoBundle\Entity\TAXREF_STATUS $reu = null)
    {
        $this->reu = $reu;

        return $this;
    }

    /**
     * Get reu.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getReu()
    {
        return $this->reu;
    }

    /**
     * Set sa.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $sa
     *
     * @return TAXREF
     */
    public function setSa(\TS\NaoBundle\Entity\TAXREF_STATUS $sa = null)
    {
        $this->sa = $sa;

        return $this;
    }

    /**
     * Get sa.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getSa()
    {
        return $this->sa;
    }

    /**
     * Set ta.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $ta
     *
     * @return TAXREF
     */
    public function setTa(\TS\NaoBundle\Entity\TAXREF_STATUS $ta = null)
    {
        $this->ta = $ta;

        return $this;
    }

    /**
     * Get ta.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getTa()
    {
        return $this->ta;
    }

    /**
     * Set nc.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $nc
     *
     * @return TAXREF
     */
    public function setNc(\TS\NaoBundle\Entity\TAXREF_STATUS $nc = null)
    {
        $this->nc = $nc;

        return $this;
    }

    /**
     * Get nc.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getNc()
    {
        return $this->nc;
    }

    /**
     * Set wf.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $wf
     *
     * @return TAXREF
     */
    public function setWf(\TS\NaoBundle\Entity\TAXREF_STATUS $wf = null)
    {
        $this->wf = $wf;

        return $this;
    }

    /**
     * Get wf.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getWf()
    {
        return $this->wf;
    }

    /**
     * Set pf.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $pf
     *
     * @return TAXREF
     */
    public function setPf(\TS\NaoBundle\Entity\TAXREF_STATUS $pf = null)
    {
        $this->pf = $pf;

        return $this;
    }

    /**
     * Get pf.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getPf()
    {
        return $this->pf;
    }

    /**
     * Set cli.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS|null $cli
     *
     * @return TAXREF
     */
    public function setCli(\TS\NaoBundle\Entity\TAXREF_STATUS $cli = null)
    {
        $this->cli = $cli;

        return $this;
    }

    /**
     * Get cli.
     *
     * @return \TS\NaoBundle\Entity\TAXREF_STATUS|null
     */
    public function getCli()
    {
        return $this->cli;
    }

    /**
     * Set regne.
     *
     * @param string $regne
     *
     * @return TAXREF
     */
    public function setRegne($regne)
    {
        $this->regne = $regne;

        return $this;
    }

    /**
     * Get regne.
     *
     * @return string
     */
    public function getRegne()
    {
        return $this->regne;
    }

    /**
     * Set phylum.
     *
     * @param string $phylum
     *
     * @return TAXREF
     */
    public function setPhylum($phylum)
    {
        $this->phylum = $phylum;

        return $this;
    }

    /**
     * Get phylum.
     *
     * @return string
     */
    public function getPhylum()
    {
        return $this->phylum;
    }

    /**
     * Set classe.
     *
     * @param string $classe
     *
     * @return TAXREF
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe.
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set ordre.
     *
     * @param string $ordre
     *
     * @return TAXREF
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre.
     *
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set famille.
     *
     * @param string $famille
     *
     * @return TAXREF
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille.
     *
     * @return string
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return TAXREF
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
     * Constructor
     */
    public function __construct()
    {
        $this->gf = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gf.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS $gf
     *
     * @return TAXREF
     */
    public function addGf(\TS\NaoBundle\Entity\TAXREF_STATUS $gf)
    {
        $this->gf[] = $gf;

        return $this;
    }

    /**
     * Remove gf.
     *
     * @param \TS\NaoBundle\Entity\TAXREF_STATUS $gf
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeGf(\TS\NaoBundle\Entity\TAXREF_STATUS $gf)
    {
        return $this->gf->removeElement($gf);
    }
}
