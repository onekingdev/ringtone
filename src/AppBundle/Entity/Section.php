<?php 
namespace AppBundle\Entity;
use MediaBundle\Entity\Media;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Section
 * @ORM\Entity
 * @ORM\Table(name="section_table")
 * @UniqueEntity(fields={"title"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectionRepository")
 */
class Section
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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     * )
     */
     
    private $title;



    /**
     * @var int
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 30,
     * )
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;



    /**
     * @var integer
     *
     * @ORM\Column(name="entity_type", type="integer", nullable=true)
     */
    private $entity_type;




    /**
    * @ORM\OneToMany(targetEntity="Category", mappedBy="section",cascade={"persist", "remove"})
    * @ORM\OrderBy({"position" = "asc"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $media;

    /**
     * @Assert\File(mimeTypes={"image/jpeg","image/png" },maxSize="4M")
     */
    private $file;



    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getCategories()
    {
        return $this->categories;
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
     * Set title
     *
     * @param string $title
     * @return Section
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
     * Set position
     *
     * @param integer $position
     * @return Section
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Add categories
     *
     * @param Category $category
     * @return Section
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }
         /**
     * Set media
     *
     * @param string $media
     * @return Article
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
    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }


    public function getEntityType() {
        return $this->entity_type;
    }

    public function setEntityType($entity_type) {
        $this->entity_type = $entity_type;
        return $this;
    }
}