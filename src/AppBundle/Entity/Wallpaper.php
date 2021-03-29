<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MediaBundle\Entity\Media;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * wallpaper
 *
 * @ORM\Table(name="wallpaper_table")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WallpaperRepository")
 */
class Wallpaper
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 100,
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    
    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;


    /**
     * @var bool
     *
     * @ORM\Column(name="review", type="boolean")
     */
    private $review;


    /**
     * @var int
     *
     * @ORM\Column(name="downloads", type="integer")
     */
    private $downloads;


    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var string
     * @ORM\Column(name="tags", type="string", length=255,nullable=true)
     */
    private $tags;



    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;



    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string",nullable=true)
     */
    private $type;


    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;



    /**
     * @var string
     *
     * @ORM\Column(name="userimage", type="string", length=255)
     */
    private $userimage;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

     /**
     * @var string
     *
     * @ORM\Column(name="wallpaper", type="string", length=255)
     */
    private $wallpaper;



    /**
     * @Assert\File(mimeTypes={"image/jpeg","image/png","image/bmp", "application/force-download", "application/octet-stream" },maxSize="40M")
     */
    private $file;



    /**
     * @var ArrayCollection
     */
    private $files;

    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;


    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255)
     */
    private $user_name;


    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;


    /**
     * @ORM\ManyToOne(targetEntity="MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;


    /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="wallpapers_categories_table",
     *      joinColumns={@ORM\JoinColumn(name="wallpaper_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id",onDelete="CASCADE")},
     *      )
     */
    private $categories;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->created= new \DateTime();
        $this->review = false;
        $this->wallpaper = "";
    }
    /**
    * Get id
    * @return  
    */
    public function getId()
    {
        return $this->id;
    }
    
    /**
    * Set id
    * @return $this
    */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ringtone
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set downloads
     *
     * @param integer $downloads
     * @return ringtone
     */
    public function setDownloads($downloads)
    {
        $this->downloads = $downloads;

        return $this;
    }

    /**
     * Get downloads
     *
     * @return integer 
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return ringtone
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }



    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ringtone
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
    * Get review
    * @return  
    */
    public function getReview()
    {
        return $this->review;
    }
    
    /**
    * Set review
    * @return $this
    */
    public function setReview($review)
    {
        $this->review = $review;
        return $this;
    }

    /**
    * Get wallpaper
    * @return string
    */
    public function getWallpaper()
    {
        return $this->wallpaper;
    }
    
    /**
    * Set wallpaper
    * @return $this
    */
    public function setWallpaper($wallpaper)
    {
        $this->wallpaper = $wallpaper;
        return $this;
    }

    public function __toString(){
        return $this->getId()." - ".$this->getTitle();
    }
    
    /**
    * Get tags
    * @return  
    */
    public function getTags()
    {
        return $this->tags;
    }
    
    /**
    * Set tags
    * @return $this
    */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    /**
    * Get description
    * @return  
    */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
    * Set description
    * @return $this
    */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
    * Get rating
    * @return  
    */
    public function getRating()
    {
        return $this->rating;
    }
    
    /**
    * Set rating
    * @return $this
    */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    public function setUserName($username) {
        $this->user_name = $username;
        return $this;
    }

    public function getUserName() {
        return $this->user_name;
    }


    public function setUserID($userID) {
        $this->user_id = $userID;
        return $this;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
        return $this;
    }

    public function getExtension() {
        return $this->extension;
    }


    public function setUserImage($userimage) {
        $this->userimage = $userimage;
        return $this;
    }

    public function getUserImage() {
        return $this->userimage;
    }

    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Album
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setFileTypesInTB($path)
    {
        $file = $this->getFile();
        switch ($file->getMimeType()) {
            case 'audio/mpeg':
               $this->setExtension("mp3");
                break;
            case 'audio/x-mpeg-3':
                $this->setExtension("mp3");
                break;
            case 'audio/x-mpeg':
                $this->setExtension("mp3");
                break;
            case 'audio/mpg':
                $this->setExtension("mp3");
                break; 
            case 'audio/mp3':
                $this->setExtension("mp3");
                break;   
            case 'application/force-download':
                $this->setExtension("mp3");
                break;   
            case 'application/octet-stream':
                $this->setExtension("mp3");
                break;           
            default:
                $this->setExtension($file->guessExtension());
                break;
        }
        $this->setType($file->getMimeType());
    }


    /**
     * Set media
     *
     * @param string $media
     * @return ringtone
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return string 
     */
    public function getMedia()
    {
        return $this->media;
    }
    
    
    /**
     * Set user
     *
     * @param string $user
     * @return ringtone
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }    

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
        /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function setCategories($categories)
    {
        return $this->categories =  $categories;
    }

    /**
     * Add categories
     *
     * @param wallpaper $categories
     * @return Category
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }
}
