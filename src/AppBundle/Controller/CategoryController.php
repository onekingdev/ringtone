<?php 
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;
use MediaBundle\Entity\Media;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class CategoryController extends Controller
{
    public function api_allAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        $categories =   $em->getRepository("AppBundle:Category")->findBy(array("entity_type"=>0),array("position"=>"asc"));
        foreach ($categories as $key => $category) {
            $s["id"]=$category->getId();
            $s["title"]=$category->getTitle();
            $s["image"]=$imagineCacheManager->getBrowserPath( $category->getMedia()->getLink(), 'section_thumb_api');
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }
    public function api_wallpaper_allAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        $categories =   $em->getRepository("AppBundle:Category")->findBy(array("entity_type"=>1),array("position"=>"asc"));
        foreach ($categories as $key => $category) {
            $s["id"]=$category->getId();
            $s["title"]=$category->getTitle();
            $s["image"]=$imagineCacheManager->getBrowserPath( $category->getMedia()->getLink(), 'section_thumb_api');
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }



    public function api_tagsAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        $tags =   $em->getRepository("AppBundle:Tag")->findBy(array(),array("search"=>"desc"));
        foreach ($tags as $key => $tag) {
            $s["id"]=$tag->getId();
            $s["name"]=$tag->getName();
            $s["search"]=$tag->getSearch();
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }
    public function api_by_sectionAction(Request $request,$id,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $section=$em->getRepository("AppBundle:Section")->find($id);

        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        $categories =   $em->getRepository("AppBundle:Category")->findBy(array("section"=>$section),array("position"=>"asc"));
        foreach ($categories as $key => $category) {
            $s["id"]=$category->getId();
            $s["title"]=$category->getTitle();
            $s["image"]=$imagineCacheManager->getBrowserPath( $category->getMedia()->getLink(), 'section_thumb_api');
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }
    public function api_byAction(Request $request,$id,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->find($id);
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");  
        }
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        foreach ($ringtone->getCategories() as $key => $category) {
            $s["id"]=$category->getId();
            $s["title"]=$category->getTitle();
            $s["image"]=$imagineCacheManager->getBrowserPath( $category->getMedia()->getLink(), 'section_thumb_api');
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }

      public function api_wallpaper_byAction(Request $request,$id,$token)
     {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->find($id);
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");  
        }
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $list=array();
        foreach ($wallpaper->getCategories() as $key => $category) {
            $s["id"]=$category->getId();
            $s["title"]=$category->getTitle();
            $s["image"]=$imagineCacheManager->getBrowserPath( $category->getMedia()->getLink(), 'section_thumb_api');
            $list[]=$s;
        }
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($list, 'json');
        return new Response($jsonContent);
    }

    public function addAction(Request $request)
    {
        $category= new Category();
        $form = $this->createForm(new CategoryType(),$category);
        $em=$this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if( $category->getFile()!=null ){
                $media= new Media();
                $media->setFile($category->getFile());
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $category->setMedia($media);
                $max=0;
                $categories=$em->getRepository('AppBundle:Category')->findBy(array("section"=>$category->getSection()));
                
                $section=$em->getRepository("AppBundle:Section")->find($category->getSection());
                $category->setEntityType ($section->getEntityType());

                foreach ($categories as $key => $value) {
                    if ($value->getPosition()>$max) {
                        $max=$value->getPosition();
                    }
                }
                $category->setPosition($max+1);
                $em->persist($category);
                $em->flush();
                $this->addFlash('success', 'Operation has been done successfully');
                return $this->redirect($this->generateUrl('app_section_view',array("id"=>$category->getSection()->getId())));
            }else{
                $error = new FormError("Required image file");
                $form->get('file')->addError($error);
            }
       }
        return $this->render("AppBundle:Category:add.html.twig",array("form"=>$form->createView()));
    }



    public function upAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $category=$em->getRepository("AppBundle:Category")->find($id);
        if ($category==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($category->getPosition()>1) {
            $p=$category->getPosition();
            $categories=$em->getRepository('AppBundle:Category')->findBy(array("section"=>$category->getSection()));
            foreach ($categories as $key => $value) {
                if ($value->getPosition()==$p-1) {
                    $value->setPosition($p);  
                }
            }
            $category->setPosition($category->getPosition()-1);
            $em->flush(); 
        }
        return $this->redirect($this->generateUrl('app_section_view',array("id"=>$category->getSection()->getId())));
    }
    public function downAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $category=$em->getRepository("AppBundle:Category")->find($id);
        if ($category==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $max=0;
        $categories=$em->getRepository('AppBundle:Category')->findBy(array("section"=>$category->getSection()),array("position"=>"asc"));
        foreach ($categories  as $key => $value) {
            $max=$value->getPosition();  
        }
        if ($category->getPosition()<$max) {
            $p=$category->getPosition();
            foreach ($categories as $key => $value) {
                if ($value->getPosition()==$p+1) {
                    $value->setPosition($p);  
                }
            }
            $category->setPosition($category->getPosition()+1);
            $em->flush();  
        }
        return $this->redirect($this->generateUrl('app_section_view',array("id"=>$category->getSection()->getId())));
    }
    public function deleteAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();

        $category = $em->getRepository("AppBundle:Category")->find($id);
        if($category==null){
            throw new NotFoundHttpException("Page not found");
        }

        $form=$this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('Yes', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $media=$category->getMedia();
            $category_old=$category;
            $em->remove($category);
            $em->flush();

            if($media!=null){
                $media->delete($this->container->getParameter('files_directory'));
                $em->remove($media);
                $em->flush();
            }
            //if (sizeof($category->getcategorys())==0) {
                $em->remove($category);
                $em->flush();

                $categories=$em->getRepository('AppBundle:Category')->findBy(array("section"=>$category_old->getSection()),array("position"=>"asc"));

                $p=1;
                foreach ($categories as $key => $value) {
                    $value->setPosition($p); 
                    $p++; 
                }
                $em->flush();

                $this->addFlash('success', 'Operation has been done successfully');
            //}else{
             //   $this->addFlash('danger', 'Operation has been cancelled ,Your category not empty');   
            //}
            return $this->redirect($this->generateUrl('app_section_view',array("id"=>$category_old->getSection()->getId())));
        }
        return $this->render('AppBundle:Category:delete.html.twig',array("form"=>$form->createView(),"category"=>$category));
    }
    public function editAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $category=$em->getRepository("AppBundle:Category")->find($id);
        if ($category==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form = $this->createForm(new CategoryType(),$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if( $category->getFile()!=null ){
                $media= new Media();
                $media_old=$category->getMedia();
                $media->setFile($category->getFile());
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $category->setMedia($media);
                $em->flush();
                $media_old->delete($this->container->getParameter('files_directory'));
                $em->remove($media_old);
                $em->flush();
            }
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_section_view',array("id"=>$category->getSection()->getId())));
 
        }
        return $this->render("AppBundle:Category:edit.html.twig",array("category"=>$category,"form"=>$form->createView()));
    }
}
?>