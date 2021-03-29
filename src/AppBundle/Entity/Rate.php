<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * Rate
 *
 * @ORM\Table(name="rate_table")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RateRepository")
 */
class Rate
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
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=255, nullable=true)
     */
    private $review;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="ringtone", inversedBy="rats")
     * @ORM\JoinColumn(name="ringtone_id", referencedColumnName="id", nullable=true)
     */
    private $ringtone;


    /**
     * @ORM\ManyToOne(targetEntity="Wallpaper", inversedBy="rats")
     * @ORM\JoinColumn(name="wallpaper_id", referencedColumnName="id", nullable=true)
     */
    private $wallpaper;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="rates")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    public function __construct()
    {
        $this->created= new \DateTime();

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
     * Set value
     *
     * @param integer $value
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return Rate
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

   /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
        /**
    * Get user
    * @return  
    */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
    * Set user
    * @return $this
    */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
                    /**
     * Set ringtone
     *
     * @param integer $ringtone
     * @return ringtone
     */
    public function setRingtone(Ringtone $ringtone)
    {
        $this->ringtone = $ringtone;

        return $this;
    }

    /**
     * Get ringtone
     *
     * @return integer 
     */
    public function getRingtone()
    {
        return $this->ringtone;
    }

    /**
     * Set wallpaper
     *
     * @param integer $wallpaper
     * @return wallpaper
     */
    public function setWallpaper(Wallpaper $wallpaper)
    {
        $this->wallpaper = $wallpaper;

        return $this;
    }

    /**
     * Get wallpaper
     *
     * @return wallpaper 
     */
    public function getWallpaper()
    {
        return $this->wallpaper;
    }
}
