<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class WallpaperReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

       $builder->add('title',null,array("label"=>"Title"));
       $builder->add('tags',null,array("label"=>"Tags"));
       $builder->add('description',null,array("label"=>"Description"));   

       $builder->add('save', 'submit',array("label"=>"REVIEW"));
    }
    public function getName()
    {
        return 'Wallpaper';
    }
}
?>