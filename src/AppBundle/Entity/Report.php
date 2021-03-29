<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report_table")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReportRepository")
 */
class Report
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
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    /**
     * @ORM\ManyToOne(targetEntity="ringtone", inversedBy="comments")
     * @ORM\JoinColumn(name="ringtone_id", referencedColumnName="id", nullable=true)
     */
    private $ringtone;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="wallpaper", inversedBy="comments")
     * @ORM\JoinColumn(name="wallpaper_id", referencedColumnName="id", nullable=true)
     */
    private $wallpaper;
    
    
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
     * Set message
     *
     * @param string $message
     * @return Report
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Report
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
     * @return integer 
     */
    public function getWallpaper()
    {
        return $this->wallpaper;
    }
}
