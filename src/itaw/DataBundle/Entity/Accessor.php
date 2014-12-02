<?php

namespace itaw\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Accessor
 *
 * @ORM\Table("accessor")
 * @ORM\Entity
 */
class Accessor
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

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
     * @ORM\JoinColumn(name="open_user_id", referencedColumnName="id")
     */
    private $openUser;

    /**
     * @ORM\ManyToOne(targetEntity="itaw\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="edit_user_id", referencedColumnName="id")
     */
    private $editUser;

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
     * Set username
     *
     * @param string $username
     * @return Accessor
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Accessor
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Accessor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Accessor
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Accessor
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
     * @return Accessor
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
     * @return Accessor
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
     * @return Accessor
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
     * @return Accessor
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
}
