<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MediaBundle\Entity\Media;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * ringtone
 *
 * @ORM\Table(name="ringtone_table")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RingtoneRepository")
 */
class Ringtone
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


        /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="ringtones_tags_table",
     *      joinColumns={@ORM\JoinColumn(name="ringtone_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id",onDelete="CASCADE")},
     *      )
     */
    private $tagslist;



    /**
     * @var string
     * @ORM\Column(name="tags", type="string", length=255,nullable=true)
     */
    private $tags;


    /**
     * @var int
     *
     * @ORM\Column(name="downloads", type="integer")
     */
    private $downloads;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;


    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="integer",nullable=true)
     */
    private $duration;

        /**
     * @var string
     *
     * @ORM\Column(name="rating", type="integer",nullable=true)
     */
    private $rating;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


     /**
     * @ORM\ManyToOne(targetEntity="MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    
    /**
     * @var bool
     *
     * @ORM\Column(name="review", type="boolean")
     */
    private $review;


     /**
     * @var bool
     *
     * @ORM\Column(name="wall", type="boolean")
     */
    private $wall;



        /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="ringtones_categories_table",
     *      joinColumns={@ORM\JoinColumn(name="ringtone_id", referencedColumnName="id",onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id",onDelete="CASCADE")},
     *      )
     */
    private $categories;


    /**
    * @ORM\OneToMany(targetEntity="Rate", mappedBy="ringtone",cascade={"persist", "remove"})
    * @ORM\OrderBy({"created" = "desc"})
    */
    private $rates;
    /**
     * @Assert\File(mimeTypes={"audio/mpeg","audio/x-mpeg-3","audio/x-mpeg","audio/mpg","audio/mp3","application/force-download","application/octet-stream","audio/x-wav","audio/wav","audio/wave","audio/vnd.wave" },maxSize="40M")
     */
    private $file;




    private $usefilename;

    private $titlemulti;

    /**
     * @var ArrayCollection
     */
    private $files;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->created= new \DateTime();
        $this->review = false;
        $this->wall = false;
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
     * Add categories
     *
     * @param ringtone $categories
     * @return Categorie
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


    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
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
    * Get wall
    * @return  
    */
    public function getWall()
    {
        return $this->wall;
    }
    
    /**
    * Set wall
    * @return $this
    */
    public function setWall($wall)
    {
        $this->wall = $wall;
        return $this;
    }
    public function __toString(){
        return $this->getId()." - ".$this->getTitle();
    }
    /**
    * Get usefilename
    * @return  
    */
    public function getUsefilename()
    {
        return $this->usefilename;
    }
    
    /**
    * Set usefilename
    * @return $this
    */
    public function setUsefilename($usefilename)
    {
        $this->usefilename = $usefilename;
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
    * Get titlemulti
    * @return  
    */
    public function getTitlemulti()
    {
        return $this->titlemulti;
    }
    
    /**
    * Set titlemulti
    * @return $this
    */
    public function setTitlemulti($titlemulti)
    {
        $this->titlemulti = $titlemulti;
        return $this;
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
    * Get duration
    * @return  
    */
    public function getDuration()
    {
        return $this->duration;
    }
    
    /**
    * Set duration
    * @return $this
    */
    public function setDuration($duration)
    {
        $this->duration = $duration;
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
          /**
     * Add tags
     *
     * @param Wallpaper $tags
     * @return tag
     */
    public function addTagslist(Tag $tagslist)
    {
        $this->tagslist[] = $tagslist;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param tag $tags
     */
    public function removeTagslist(Tag $tagslist)
    {
        $this->tagslist->removeElement($tagslist);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTagslist()
    {
        return $this->tagslist;
    }
        /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function setTagslist($tagslist)
    {
        return $this->tagslist =  $tagslist;
    }

}
