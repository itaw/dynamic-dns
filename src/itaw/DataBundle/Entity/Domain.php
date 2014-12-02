<?php

namespace itaw\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 *
 * @ORM\Table("domain")
 * @ORM\Entity
 */
class Domain
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="editDate", type="datetime", nullable=true)
     */
    private $editDate;

    /**
     * @ORM\ManyToOne(targetEntity="itaw\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="edit_user_id", referencedColumnName="id")
     */
    private $openUser;

    /**
     * @ORM\ManyToOne(targetEntity="itaw\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="open_user_id", referencedColumnName="id")
     */
    private $editUser;

    /**
     * @ORM\OneToMany(targetEntity="DomainAddress", mappedBy="domain")
     */
    private $domainAddresses;

    public function __construct()
    {
        $this->domainAddresses = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Domain
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Domain
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set editDate
     *
     * @param \DateTime $editDate
     * @return Domain
     */
    public function setEditDate($editDate)
    {
        $this->editDate = $editDate;

        return $this;
    }

    /**
     * Get editDate
     *
     * @return \DateTime 
     */
    public function getEditDate()
    {
        return $this->editDate;
    }

    /**
     * Set openUser
     *
     * @param \itaw\UserBundle\Entity\User $openUser
     * @return Domain
     */
    public function setOpenUser(\itaw\UserBundle\Entity\User $openUser = null)
    {
        $this->openUser = $openUser;

        return $this;
    }

    /**
     * Get openUser
     *
     * @return \itaw\UserBundle\Entity\User 
     */
    public function getOpenUser()
    {
        return $this->openUser;
    }

    /**
     * Set editUser
     *
     * @param \itaw\UserBundle\Entity\User $editUser
     * @return Domain
     */
    public function setEditUser(\itaw\UserBundle\Entity\User $editUser = null)
    {
        $this->editUser = $editUser;

        return $this;
    }

    /**
     * Get editUser
     *
     * @return \itaw\UserBundle\Entity\User 
     */
    public function getEditUser()
    {
        return $this->editUser;
    }

    /**
     * Add domainAddresses
     *
     * @param \itaw\DataBundle\Entity\DomainAddress $domainAddresses
     * @return Domain
     */
    public function addDomainAddress(\itaw\DataBundle\Entity\DomainAddress $domainAddresses)
    {
        $this->domainAddresses[] = $domainAddresses;

        return $this;
    }

    /**
     * Remove domainAddresses
     *
     * @param \itaw\DataBundle\Entity\DomainAddress $domainAddresses
     */
    public function removeDomainAddress(\itaw\DataBundle\Entity\DomainAddress $domainAddresses)
    {
        $this->domainAddresses->removeElement($domainAddresses);
    }

    /**
     * Get domainAddresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDomainAddresses()
    {
        return $this->domainAddresses;
    }
}
