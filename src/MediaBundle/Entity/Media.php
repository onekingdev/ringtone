<?php

namespace MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media
 *
 * @ORM\Table(name="media_table")
 * @ORM\Entity(repositoryClass="MediaBundle\Repository\MediaRepository")
 */
class Media
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    // ...

    /**
     * @Assert\NotBlank()
     * @Assert\File(mimeTypes={"image/gif","image/jpeg","image/png" },maxSize="10M")
     */
    private $file;


    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;
    public function __construct()
    {
        $this->date= new \DateTime();
        $this->enabled=false;
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
     * Set titre
     *
     * @param string $titre
     * @return Media
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Media
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getLink()
    {
        return "uploads/".$this->url;
    }   

    /**
     * Set type
     *
     * @param string $type
     * @return Media
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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
    public function upload($path)
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
        $fileName = md5(uniqid()).'.'.$this->getExtension();
        $this->setTitre($file->getClientOriginalName());
        $this->setUrl($fileName);
        $this->setType($file->getMimeType());
        $file->move(
            $path,
            $fileName
        );

        return $fileName;
    }
    public  function delete($url)
    {
       // if ($this->getType()=="image") {
            @unlink($url.$this->getUrl());
       // } 
    }
    public function addVideo($url)
    {
        $video_id = explode("?v=", $url); // For videos like http://www.youtube.com/watch?v=...
        if (empty($video_id[1]))
            $video_id = explode("/v/", $url); // For videos like http://www.youtube.com/watch/v/..

        $video_id = explode("&", $video_id[1]); // Deleting any other params
        $video_id = $video_id[0];
        $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$video_id);
        parse_str($content, $ytarr);
        if($ytarr['title']!=null){
            $this->setTitre($ytarr['title']);
        }else{}
        $this->setUrl($url);
        $this->setType("youtube");
    }
     public function getImage()
    {
        try {
            

        $video_id = @explode("?v=", $this->url); // For videos like http://www.youtube.com/watch?v=...
        if (empty($video_id[1]))
            $video_id = @explode("/v/", $url); // For videos like http://www.youtube.com/watch/v/..

        $video_id = @explode("&", $video_id[1]); // Deleting any other params
        $video_id = $video_id[0];
        } catch (Exception $e) {
            $video_id="iojzejfo";
        }
        return "http://img.youtube.com/vi/". $video_id."/hqdefault.jpg";
    }
    public function getImageL()
    {
        try {
            

        $video_id = @explode("?v=", $this->url); // For videos like http://www.youtube.com/watch?v=...
        if (empty($video_id[1]))
            $video_id = @explode("/v/", $url); // For videos like http://www.youtube.com/watch/v/..

        $video_id = @explode("&", $video_id[1]); // Deleting any other params
        $video_id = $video_id[0];
        } catch (Exception $e) {
            $video_id="iojzejfo";
        }
        return "http://img.youtube.com/vi/". $video_id."/maxresdefault.jpg";
    } 
    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Annonce
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Article
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
    * Get extension
    * @return  
    */
    public function getExtension()
    {
        return $this->extension;
    }
    
    /**
    * Set extension
    * @return $this
    */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }
}
