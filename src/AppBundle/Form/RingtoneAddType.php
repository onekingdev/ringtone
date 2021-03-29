<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class RingtoneAddType extends AbstractType
{
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('title',null,array("label"=>"Title"));
       $builder->add('tags',null,array("label"=>"Tags"));
       $builder->add('description',null,array("label"=>"Description"));
       $builder->add('title',null,array("label"=>"Title"));
       $builder->add('enabled',null,array("label"=>"Enabled"));
       $builder->add("categories",'entity',
                    array(
                          'class' => 'AppBundle:Category',
                          'expanded' => true,
                          "choices" => $this->filterEntities(),
                          "multiple" => "true",
                          'by_reference' => false,
                        )
                    );
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $article = $event->getData();
            $form = $event->getForm();
            if ($article and null !== $article->getId()) {
                 $form->add('files', FileType::class, [
                    "label"=>"",
                    "required"=>false,
                    'multiple' => true,
                    'attr'     => [
                        'multiple' => 'multiple'
                    ]
                ]);
            }else{
                 $form->add('files', FileType::class, [
                    "label"=>"",
                    "required"=>true,
                    'multiple' => true,
                    'attr'     => [
                        'multiple' => 'multiple'
                    ]
                ]);
            }
        });
       $builder->add('save', 'submit',array("label"=>"save"));
    }
    public function getName()
    {
        return 'Ringtone';
    }
    private function filterEntities() {
        $repository = $this->em->getRepository('AppBundle:Category');
        $query_dql = $repository->createQueryBuilder('w')
          ->where("w.entity_type = 0")
          ->addOrderBy('w.position', 'asc')
          ->getQuery();
        $results = $query_dql->getResult();

        return $results;
    }
}
?>