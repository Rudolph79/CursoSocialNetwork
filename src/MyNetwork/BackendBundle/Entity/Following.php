<?php

namespace MyNetwork\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Following
 *
 * @ORM\Table(name="following", indexes={@ORM\Index(name="fk_following_users", columns={"user"}), @ORM\Index(name="fk_followed", columns={"followed"})})
 * @ORM\Entity
 */
class Following
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="followed", referencedColumnName="id")
     * })
     */
    private $followed;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;



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
     * Set followed
     *
     * @param \MyNetwork\BackendBundle\Entity\Users $followed
     *
     * @return Following
     */
    public function setFollowed(\MyNetwork\BackendBundle\Entity\Users $followed = null)
    {
        $this->followed = $followed;

        return $this;
    }

    /**
     * Get followed
     *
     * @return \MyNetwork\BackendBundle\Entity\Users
     */
    public function getFollowed()
    {
        return $this->followed;
    }

    /**
     * Set user
     *
     * @param \MyNetwork\BackendBundle\Entity\Users $user
     *
     * @return Following
     */
    public function setUser(\MyNetwork\BackendBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MyNetwork\BackendBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }
}
