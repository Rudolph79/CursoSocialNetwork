<?php

namespace MyNetwork\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes", indexes={@ORM\Index(name="fk_like_users", columns={"user"}), @ORM\Index(name="fk_likes_publication", columns={"publication"})})
 * @ORM\Entity
 */
class Likes
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
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Publications
     *
     * @ORM\ManyToOne(targetEntity="Publications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="publication", referencedColumnName="id")
     * })
     */
    private $publication;



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
     * Set user
     *
     * @param \MyNetwork\BackendBundle\Entity\Users $user
     *
     * @return Likes
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

    /**
     * Set publication
     *
     * @param \MyNetwork\BackendBundle\Entity\Publications $publication
     *
     * @return Likes
     */
    public function setPublication(\MyNetwork\BackendBundle\Entity\Publications $publication = null)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return \MyNetwork\BackendBundle\Entity\Publications
     */
    public function getPublication()
    {
        return $this->publication;
    }
}
