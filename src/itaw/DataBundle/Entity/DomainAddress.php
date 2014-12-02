<?php

namespace itaw\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DomainAddress
 *
 * @ORM\Table("domain_address")
 * @ORM\Entity
 */
class DomainAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="openDate", type="datetime")
     */
    private $openDate;

    /**
     * @var string
     *
     * @ORM\Column(name="sourceIp", type="string", length=255)
     */
    private $sourceIp;

    /**
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="domainAddresses")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    private $domain;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return DomainAddress
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set openDate
     *
     * @param \DateTime $openDate
     * @return DomainAddress
     */
    public function setOpenDate($openDate)
    {
        $this->openDate = $openDate;

        return $this;
    }

    /**
     * Get openDate
     *
     * @return \DateTime
     */
    public function getOpenDate()
    {
        return $this->openDate;
    }

    /**
     * Set sourceIp
     *
     * @param string $sourceIp
     * @return DomainAddress
     */
    public function setSourceIp($sourceIp)
    {
        $this->sourceIp = $sourceIp;

        return $this;
    }

    /**
     * Get sourceIp
     *
     * @return string
     */
    public function getSourceIp()
    {
        return $this->sourceIp;
    }

    /**
     * Set domain
     *
     * @param \itaw\DataBundle\Entity\Domain $domain
     * @return DomainAddress
     */
    public function setDomain(\itaw\DataBundle\Entity\Domain $domain = null)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \itaw\DataBundle\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
