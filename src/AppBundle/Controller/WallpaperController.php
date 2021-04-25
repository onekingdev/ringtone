<?php 
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Wallpaper;
use MediaBundle\Entity\Media;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Rate;
use AppBundle\Entity\MP3File;
use AppBundle\Form\WallpaperMultiType;
use AppBundle\Form\WallpaperType;
use AppBundle\Form\WallpaperAddType;
use AppBundle\Form\WallpaperEditType;
use AppBundle\Form\WallpaperReviewType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;

CliDumper::$defaultOutput = 'php://output';
VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $dumper = new CliDumper();
    $dumper->dump($cloner->cloneVar($var));
});

class WallpaperController extends Controller
{


  	public function format_size($size) {
  	  if ($size < 1000) {
  	    return $size . ' B';
  	  }
  	  else {
  	    $size = $size / 1000;
  	    $units = ['KB', 'MB', 'GB', 'TB'];
  	    foreach ($units as $unit) {
  	      if (round($size,2) >= 1000) {
  	        $size = $size / 1000;
  	      }
  	      else {
  	        break;
  	      }
  	    }
  	    return round($size, 2) . ' ' . $unit;
  	  }
  	}

    public function addAction(Request $request)
    {
        $wallpaper= new Wallpaper();
        $em=$this->getDoctrine()->getManager();
        $form = $this->createForm(new WallpaperAddType($em),$wallpaper);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	if( $wallpaper->getFiles()!=null ){
                $files = $wallpaper->getFiles();
                foreach($files as $file)
                {
                    $media= new Media();
                    $new_wallpaper = new Wallpaper();
                    $media->setFile($file);
                    $new_wallpaper->setFile($file);
                    $new_wallpaper->setFiles($files);
                    // $new_wallpaper->setTitle($wallpaper->getTitle());
                    $new_wallpaper->setTitle($file->getClientOriginalName());
                    $new_wallpaper->setTags($wallpaper->getTags());
                    $new_wallpaper->setEnabled($wallpaper->getEnabled());
                    $new_wallpaper->setCategories($wallpaper->getCategories());
                    $new_wallpaper->setDescription($wallpaper->getDescription());
                    $new_wallpaper->setFileTypesInTB($this->container->getParameter('files_directory'));
                    $media->upload($this->container->getParameter('files_directory'));
                    $new_wallpaper->setWallpaper($media->getUrl());
                    $new_wallpaper->setMedia($media);
                    $em->persist($media);
                    $em->flush();
                    $size = $this->format_size($file->getClientSize());
                    $new_wallpaper->setUser($this->getUser());
                    $new_wallpaper->setUserID($this->getUser()->getId());
                    $new_wallpaper->setUserName($this->getUser()->getName());
                    $new_wallpaper->setDownloads(0);
                    $new_wallpaper->setSize($size);
                    $new_wallpaper->setUserImage($this->getUser()->getImage());
                    $em->persist($new_wallpaper);
                    $em->flush();
                }
                $this->addFlash('success', 'Operation has been done successfully');
                return $this->redirect($this->generateUrl('app_wallpaper_index'));
            }else{
                $error = new FormError("Required image file");
                $form->get('file')->addError($error);
            }
 		}
        return $this->render("AppBundle:Wallpaper:add.html.twig",array("form"=>$form->createView()));
    }
    public function indexAction(Request $request)
    {
    	$em=$this->getDoctrine()->getManager();
        $q="  ";
        $searchText = "";
        if ($request->query->has("like") and $request->query->get("like")!="") {
            $q.=" AND  w.title like '%".$request->query->get("like")."%'";
            $searchText = $request->query->get("like");
        } else {
            if ($request->query->has("q") and $request->query->get("q")!="") {
                $q.=" AND  w.title like '%".$request->query->get("q")."%'";
            }
        }
        $dql        = "SELECT w FROM AppBundle:Wallpaper w  WHERE w.review = false ".$q ." ORDER BY w.created desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $wallpapers = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            12
        );
        
        $wallpapers_list=$em->getRepository('AppBundle:Wallpaper')->findAll();
	      $wallpapers_count= sizeof($wallpapers_list);
	      return $this->render('AppBundle:Wallpaper:index.html.twig',array("wallpapers"=>$wallpapers,"wallpapers_count"=>$wallpapers_count, "searchText"=>$searchText));    
    }

    public function reviewsAction(Request $request)
    {

      $em=$this->getDoctrine()->getManager();
      
        $dql        = "SELECT w FROM AppBundle:Wallpaper w  WHERE w.review = true ORDER BY w.created desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $wallpapers = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            12
        );
        $wallpapers_list=$em->getRepository('AppBundle:Wallpaper')->findBy(array("review"=>true));
        $wallpapers_count= sizeof($wallpapers_list);
        return $this->render('AppBundle:Wallpaper:reviews.html.twig',array("wallpapers"=>$wallpapers,"wallpapers_count"=>$wallpapers_count));    
    }
    public function deleteAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $wallpaper = $em->getRepository("AppBundle:Wallpaper")->find($id);
        if($wallpaper==null){
            throw new NotFoundHttpException("Page not found");
        }

        $form=$this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('Yes', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        	if( $wallpaper->getMedia()!=null ){
                $media_old=$wallpaper->getMedia();
                $media_old->delete($this->container->getParameter('files_directory'));
                $em->remove($wallpaper);
                $em->flush();
	        }

            $em->remove($media_old);
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_wallpaper_index'));
        }
        return $this->render('AppBundle:Wallpaper:delete.html.twig',array("form"=>$form->createView()));
    }
    
    public function editAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->findOneBy(array("id"=>$id,"review"=>false));
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form = $this->createForm(new WallpaperType($em),$wallpaper);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if( $wallpaper->getFile()!=null ){
                $file = $wallpaper->getFile();
                $media= new Media();
                $media_old=$wallpaper->getMedia();
                $media->setFile($file);
                $wallpaper->setFileTypesInTB($this->container->getParameter('files_directory'));
                $media->upload($this->container->getParameter('files_directory'));
                $wallpaper->setWallpaper($media->getUrl());
                $wallpaper->setMedia($media);
                $em->persist($media);
                $em->flush();
                $size = $this->format_size($file->getClientSize());
                $wallpaper->setUserID($this->getUser()->getId());
                $wallpaper->setUserName($this->getUser()->getName());
                $wallpaper->setDownloads(0);
                $wallpaper->setSize($size);
                $wallpaper->setUserImage($this->getUser()->getImage());

                $media_old->delete($this->container->getParameter('files_directory'));
                $em->remove($media_old);
            }
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_wallpaper_index'));
        }
        return $this->render("AppBundle:Wallpaper:edit.html.twig",array("form"=>$form->createView()));
    }
    public function reviewAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->findOneBy(array("id"=>$id,"review"=>true));
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form = $this->createForm(new WallpaperReviewType(),$wallpaper);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $wallpaper->setReview(false);
            $wallpaper->setEnabled(true);
            $wallpaper->setCreated(new \DateTime());
            $em->persist($wallpaper);
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_home_notif_user_wallpaper',array("wallpaper_id"=>$wallpaper->getId())));
        }
        return $this->render("AppBundle:Wallpaper:review.html.twig",array("form"=>$form->createView()));
    }
     public function viewAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->find($id);
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");
        }

        $rates_1 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper,"value"=>1));
        $rates_2 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper,"value"=>2));
        $rates_3 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper,"value"=>3));
        $rates_4 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper,"value"=>4));
        $rates_5 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper,"value"=>5));
        $rates = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$wallpaper));


        $ratings["rate_1"]=sizeof($rates_1);
        $ratings["rate_2"]=sizeof($rates_2);
        $ratings["rate_3"]=sizeof($rates_3);
        $ratings["rate_4"]=sizeof($rates_4);
        $ratings["rate_5"]=sizeof($rates_5);


        $t = sizeof($rates_1) + sizeof($rates_2) +sizeof($rates_3)+ sizeof($rates_4) + sizeof($rates_5);
        if ($t == 0) {
            $t=1;
        }
        $values["rate_1"]=(sizeof($rates_1)*100)/$t;
        $values["rate_2"]=(sizeof($rates_2)*100)/$t;
        $values["rate_3"]=(sizeof($rates_3)*100)/$t;
        $values["rate_4"]=(sizeof($rates_4)*100)/$t;
        $values["rate_5"]=(sizeof($rates_5)*100)/$t;

        $total=0;
        $count=0;
        foreach ($rates as $key => $r) {
           $total+=$r->getValue();
           $count++;
        }
        $v=0;
        if ($count != 0) {
            $v=$total/$count;
        }
        $rating=$v;
        return $this->render("AppBundle:Wallpaper:view.html.twig",array("wallpaper"=>$wallpaper,"rating"=>$rating,"ratings"=>$ratings,"values"=>$values));
    }
    public function api_getAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->findOneBy(array("id"=>$id,"enabled"=>true));
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render("AppBundle:Wallpaper:api_get.html.php",array("wallpaper"=>$wallpaper));
    }
    public function api_allAction(Request $request,$page,$order,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');
        $query = $repository->createQueryBuilder('w')
        ->where("w.enabled = true")
        ->addOrderBy('w.'.$order, 'DESC')
        ->addOrderBy('w.id', 'asc')
        ->setFirstResult($nombre*$page)
        ->setMaxResults($nombre)
        ->getQuery();
        $wallpapers = $query->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    public function api_relatedAction(Request $request,$id,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }

        $nombre=30;
        
        $em=$this->getDoctrine()->getManager();

        $wallpaper = $em->getRepository('AppBundle:Wallpaper')->findOneBy(array("enabled"=>true,"id"=>$id));
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");  
        }

        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');


        $categories="";
        foreach ($wallpaper->getCategories() as $key => $category) {
           $categories=$categories.",".$category->getId();
        }


        if ($categories=="") {
            $query = $repository->createQueryBuilder('w')
            ->where("w.enabled = true","w.id != :wallpaper")
            ->setParameter('wallpaper', $wallpaper)
            ->addOrderBy('w.downloads', 'DESC')
            ->addOrderBy('w.id', 'asc')
            ->setFirstResult(0)
            ->setMaxResults($nombre)
            ->getQuery(); 
        }else{

            $categories = trim($categories,",");

            $query = $repository->createQueryBuilder('w')
            ->leftJoin('w.categories', 'c')
            ->where('c.id in ('.$categories.')',"w.enabled = true","w.id != :wallpaper")
            ->setParameter('wallpaper', $wallpaper)
            ->addOrderBy('w.downloads', 'DESC')
            ->addOrderBy('w.id', 'asc')
            ->setFirstResult(0)
            ->setMaxResults($nombre)
            ->getQuery();
        }   

        $wallpapers = $query->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    public function api_by_categoryAction(Request $request,$page,$category,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');
        
        $query = $repository->createQueryBuilder('w')
          ->leftJoin('w.categories', 'c')
          ->where('c.id = :category',"w.enabled = true")
          ->setParameter('category', $category)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        
        $wallpapers = $query->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    public function api_by_userAction(Request $request,$page,$user,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');
        
        $query = $repository->createQueryBuilder('w')
          ->where('w.user = :user',"w.enabled = true")
          ->setParameter('user', $user)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        $wallpapers = $query->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    public function api_by_meAction(Request $request,$page,$user,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');
        
        $query = $repository->createQueryBuilder('w')
          ->where('w.user = :user')
          ->setParameter('user', $user)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        $wallpapers = $query->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    
    public function api_by_queryAction(Request $request,$page,$query,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Wallpaper');
        
        $query_dql = $repository->createQueryBuilder('w')
          ->where("w.enabled = true","LOWER(w.title) like LOWER('%".$query."%')")
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        
        $wallpapers = $query_dql->getResult();
        return $this->render('AppBundle:Wallpaper:api_first.html.php',array("wallpapers"=>$wallpapers));
    }
    public function api_add_downloadAction(Request $request,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $id = $request->get("id");        
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->find($id);
        $wallpaper->setDownloads($wallpaper->getDownloads()+1);
        $em->flush();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($wallpaper->getDownloads(), 'json');
        return new Response($jsonContent);
    }
    public function api_add_setAction(Request $request,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $id = $request->get("id");        
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->find($id);
        $wallpaper->setSets($wallpaper->getSets()+1);
        $em->flush();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($wallpaper->getDownloads(), 'json');
        return new Response($jsonContent);
    }


    public function api_add_rateAction($user,$wallpaper,$value,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $a=$em->getRepository('AppBundle:Wallpaper')->find($wallpaper);
        $u=$em->getRepository("UserBundle:User")->find($user);
        $code="200";
        $message="";
        $errors=array();

        if ($u!=null and $a!=null) {

            $rate =  $em->getRepository('AppBundle:Rate')->findOneBy(array("user"=>$u,"wallpaper"=>$a));
            if ($rate == null) {
                $rate_obj = new Rate();
                $rate_obj->setValue($value);
                $rate_obj->setWallpaper($a);
                $rate_obj->setUser($u);
                $em->persist($rate_obj);
                $em->flush();
                $message="Your Ratting has been added";

            }else{
                $rate->setValue($value);
                $em->flush();
                $message="Your Ratting has been edit";

            }
        }else{
            $code="500";
            $message="Sorry, your rate could not be added at this time";
        }
        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>$errors
        );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }
    public function api_get_rateAction($user=null,$wallpaper,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $a=$em->getRepository('AppBundle:Wallpaper')->find($wallpaper);
        $u=$em->getRepository("UserBundle:User")->find($user);
        $code="200";
        $message="";
        $errors=array();

        if ($a!=null) {
            $rates_1 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a,"value"=>1));
            $rates_2 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a,"value"=>2));
            $rates_3 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a,"value"=>3));
            $rates_4 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a,"value"=>4));
            $rates_5 = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a,"value"=>5));
            $rates = $em->getRepository('AppBundle:Rate')->findBy(array("wallpaper"=>$a));
            $rate = null;
            if ($u!=null) {
                $rate =  $em->getRepository('AppBundle:Rate')->findOneBy(array("user"=>$u,"wallpaper"=>$a));
            }
            if ($rate == null) {
                $code="202";
            }else{
                $message= $rate->getValue();
            }

            $errors[]=array("name"=>"1","value"=>sizeof($rates_1));
            $errors[]=array("name"=>"2","value"=>sizeof($rates_2));
            $errors[]=array("name"=>"3","value"=>sizeof($rates_3));
            $errors[]=array("name"=>"4","value"=>sizeof($rates_4));
            $errors[]=array("name"=>"5","value"=>sizeof($rates_5));
            $total=0;
            $count=0;
            foreach ($rates as $key => $r) {
               $total+=$r->getValue();
               $count++;
            }
            $v=0;
            if ($count != 0) {
                $v=$total/$count;
            }
            $v2=number_format((float)$v, 1, '.', '');
            $errors[]=array("name"=>"rate","value"=>$v2);



        }else{
            $code="500";
            $message="Sorry, your rate could not be added at this time";
        }
        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>$errors
        );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }

    public function api_uploadAction(Request $request,$token)
    {
        $id=str_replace('"', '', $request->get("id"));
        $key=str_replace('"', '', $request->get("key"));
        $title=str_replace('"', '', $request->get("title"));

        $categories_ids=str_replace('"', '',$request->get("categories"));
        $categories_list = explode( "_",$categories_ids);
        $code="200";
        $message="Ok";
        $values=array();
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("id"=>$id));  
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if (sha1($user->getPassword()) != $key) {
           throw new NotFoundHttpException("Page not found");
        }   
        if ($user) {     

            if ($this->getRequest()->files->has('uploaded_file')) {
                // $old_media=$user->getMedia();
                $file = $this->getRequest()->files->get('uploaded_file');

                $w= new Wallpaper();
                $media= new Media();

                $media->setFile($file);
                //$w->setFileTypesInTB($this->container->getParameter('files_directory'));
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $size = $this->format_size($file->getClientSize());
                $url  = $this->container->getParameter('files_directory').$media->getUrl();
                
                foreach ($categories_list as $key => $id_category) {
                  $category_obj = $em->getRepository('AppBundle:Category')->find($id_category); 
                  if ($category_obj) {
                      $w->addCategory($category_obj);
                  }
                }
		$w->setUser($user);
                $w->setUserID($user->getId());
               	$w->setUserName($user->getName());
                $w->setUserImage($user->getImage());

                $w->setDownloads(0);
                $w->setEnabled(false);
                $w->setReview(true);
                $w->setTitle($title);
                $w->setSize($size);
                $w->setMedia($media);
                $em->persist($w);
                $em->flush();
            }
        }
        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>$values
            );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }

    public function multiAction(Request $request)
    {
        $wallpaper= new Wallpaper();
        $form = $this->createForm(new WallpaperMultiType(),$wallpaper);
        $em=$this->getDoctrine()->getManager();
        $colors=$em->getRepository('AppBundle:Color')->findBy(array(),array("position"=>"asc"));
        $wallpaper->setTitle("new Wallpaper");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           if( $wallpaper->getFiles()!=null ){
            $all=0;
                $valide=0;
                $pos=1;
                foreach($wallpaper->getFiles() as $k => $file){
                    $type= $file->getMimeType(); 
                    $all++;
                    if ($type=="image/jpeg" or $type=="image/png") {  
                        $media= new Media();
                        $resolution= $this->cssifysize($file);
                        $media->setFile($file);
                        $media->upload($this->container->getParameter('files_directory'));
                        $em->persist($media);
                        $em->flush();
                        $size = $this->format_size($file->getClientSize());
                        $url  = $this->container->getParameter('files_directory').$media->getUrl();
                        $color= $this->getMainColor($url);
                        $w= new Wallpaper();
                        $w->setDownloads(0);
                        $w->setTags($wallpaper->getTags());
                        $w->setDescription($wallpaper->getDescription());
                        $w->setColors($wallpaper->getColors());
                        $w->setComment($wallpaper->getComment());
                        $w->setEnabled($wallpaper->getEnabled());
                        if ($wallpaper->getUsefilename()) {
                          $w->setTitle(preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName()));
                        }else{
                          $w->setTitle($wallpaper->getTitlemulti());
                        }
                        $w->setUser($this->getUser());
                        $w->setSets(0);
                        $w->setColor("#".$color);
                        $w->setResolution($resolution);
                        $w->setSize($size);
                        $w->setMedia($media);
                        $em->persist($w);
                        $em->flush();
                        $valide++;
                        $pos++;
                        //$audio->setMedia($media);
                      }
                }  
                $this->addFlash('success', 'Operation has been done successfully, Image uploaded '.$valide."/".$all);
                return $this->redirect($this->generateUrl('app_wallpaper_index'));
            }else{
              $error = new FormError("Required image file");
              $form->get('files')->addError($error);
         }
    }
        return $this->render("AppBundle:Wallpaper:multi.html.twig",array("form"=>$form->createView(),"colors"=>$colors));
    }
    public function shareAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $wallpaper=$em->getRepository("AppBundle:Wallpaper")->find($id);
        if ($wallpaper==null) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render("AppBundle:Wallpaper:share.html.twig",array("wallpaper"=>$wallpaper));
    }
    function wavDur($file) {
      $fp = fopen($file, 'r');
      if (fread($fp,4) == "RIFF") {
        fseek($fp, 20);
        $rawheader = fread($fp, 16);
        $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits',$rawheader);
        $pos = ftell($fp);
        while (fread($fp,4) != "data" && !feof($fp)) {
          $pos++;
          fseek($fp,$pos);
        }
        $rawheader = fread($fp, 4);
        $data = unpack('Vdatasize',$rawheader);
        $sec = $data["datasize"]/$header["bytespersec"];

        return (int) $sec;
      }
    }

}
?>