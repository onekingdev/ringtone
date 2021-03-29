<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Device;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class HomeController extends Controller
{
 

  function send_notificationToken ($tokens, $message,$key)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids'  => $tokens,
            'data'   => $message

            );
        $headers = array(
            'Authorization:key = '.$key,
            'Content-Type: application/json'
            );
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
    }
    function send_notification ($message,$key)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to'  => '/topics/RingtoneTopic',
            'data'   => $message

            );
        $headers = array(
            'Authorization:key = '.$key,
            'Content-Type: application/json'
            );
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
    }

   
    public function notifCategoryAction(Request $request)
    {
        
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $em=$this->getDoctrine()->getManager();
        $categories= $em->getRepository("AppBundle:Category")->findAll();
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)         
            ->add('category', 'entity', array('class' => 'AppBundle:Category'))           
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('image', UrlType::class,array("label"=>"Big Picture"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $category_selected = $em->getRepository("AppBundle:Category")->find($data["category"]);
            $message = array(
                        "type"=>"category",
                        "id"=>$category_selected->getId(),
                        "title_category"=>$category_selected->getTitle(),
                        "image_category"=>$imagineCacheManager->getBrowserPath( $category_selected->getMedia()->getLink(), 'section_thumb_api'),
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "image"=> $data["image"],
                        "icon"=>$data["icon"]
                        );
            $key=$this->container->getParameter('fire_base_key');
            $message_status = $this->send_notification($message,$key); 
            $this->addFlash('success', 'Operation has been done successfully ');
        }
        return $this->render('AppBundle:Home:notif_category.html.twig',array("form"=>$form->createView()));
    }
   public function notifUrlAction(Request $request)
    {
    
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $em=$this->getDoctrine()->getManager();
        $categories= $em->getRepository("AppBundle:Category")->findAll();
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)
            ->add('url', UrlType::class,array("label"=>"Url"))
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('image', UrlType::class,array("label"=>"Big Picture"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $message = array(
                        "type"=>"link",
                        "id"=>strlen($data["url"]),
                        "link"=>$data["url"],
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "image"=> $data["image"],
                        "icon"=>$data["icon"]
                        );
           $key=$this->container->getParameter('fire_base_key');

          
          $message_status = $this->send_notification($message,$key); 
          $this->addFlash('success', 'Operation has been done successfully ');
            


        }
        return $this->render('AppBundle:Home:notif_url.html.twig',array("form"=>$form->createView() ));
    }
    public function notifRingtoneAction(Request $request)
    {
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $em=$this->getDoctrine()->getManager();
        $ringtones= $em->getRepository("AppBundle:Ringtone")->findBy(array("enabled"=>true));
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)
            ->add('ringtone', 'entity', array('class' => 'AppBundle:Ringtone'))           
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('image', UrlType::class,array("label"=>"Big Picture"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $ringtone_selected = $em->getRepository("AppBundle:Ringtone")->find($data["ringtone"]);
            $message = array(
                        "type"=>"ringtone",
                        "id"=>$ringtone_selected->getId(),
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "image"=> $data["image"],
                        "icon"=>$data["icon"]
                        );
            $key=$this->container->getParameter('fire_base_key');
            $message_status = $this->send_notification($message,$key); 
                      echo $message_status;

           // $this->addFlash('success', 'Operation has been done successfully ');
        }
        return $this->render('AppBundle:Home:notif.html.twig',array("form"=>$form->createView()));
    }


    public function notifWallpaperAction(Request $request)
    {
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $em=$this->getDoctrine()->getManager();
        $wallpapers= $em->getRepository("AppBundle:Wallpaper")->findBy(array("enabled"=>true));
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('message', TextareaType::class)
            ->add('wallpaper', 'entity', array('class' => 'AppBundle:Wallpaper'))           
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('image', UrlType::class,array("label"=>"Big Picture"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $wallpaper_selected = $em->getRepository("AppBundle:Wallpaper")->find($data["wallpaper"]);
            $message = array(
                        "type"=>"wallpaper",
                        "id"=>$wallpaper_selected->getId(),
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "image"=> $data["image"],
                        "icon"=>$data["icon"]
                        );
            $key=$this->container->getParameter('fire_base_key');
            $message_status = $this->send_notification($message,$key); 
                      echo $message_status;

           // $this->addFlash('success', 'Operation has been done successfully ');
        }
        return $this->render('AppBundle:Home:notif.html.twig',array("form"=>$form->createView()));
    }


    public function notifUserWallpaperAction(Request $request)
    {
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $wallpaper_id= $request->query->get("wallpaper_id");
            $em=$this->getDoctrine()->getManager();


        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('wallpaper', HiddenType::class,array("attr"=>array("value"=>$wallpaper_id)))
            ->add('message', TextareaType::class)
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $data = $form->getData();
            $wallpaper= $em->getRepository("AppBundle:Wallpaper")->find($data["wallpaper"]);
            $user= $this->getUser();

            
             if ($user==null) {
                throw new NotFoundHttpException("Page not found");  
            }
            
            $tokens[]=$user->getToken();
        
            $message = array(
                        "type"=>"wallpaper",
                        "id"=>$wallpaper->getId(),
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "icon"=>$data["icon"]
                        );
            $key=$this->container->getParameter('fire_base_key');

            $message_status = $this->send_notificationToken($tokens, $message,$key); 
            
            $this->addFlash('success', 'Operation has been done successfully ');
            return $this->redirect($this->generateUrl('app_wallpaper_index'));

        }else{
           $wallpaper= $em->getRepository("AppBundle:Wallpaper")->find($wallpaper_id);
        }
        return $this->render('AppBundle:Home:notif_user_wallpaper.html.twig',array(
          "form"=>$form->createView(),
          'wallpaper'=>$wallpaper
        ));
    }


    public function notifUserAction(Request $request)
    {
        
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $ringtone_id= $request->query->get("ringtone_id");
            $em=$this->getDoctrine()->getManager();


        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->setMethod('GET')
            ->add('title', TextType::class)
            ->add('ringtone', HiddenType::class,array("attr"=>array("value"=>$ringtone_id)))
            ->add('message', TextareaType::class)
            ->add('icon', UrlType::class,array("label"=>"Large Icon"))
            ->add('send', SubmitType::class,array("label"=>"Send notification"))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
                        $data = $form->getData();
            $ringtone= $em->getRepository("AppBundle:Ringtone")->find($data["ringtone"]);
            $user= $ringtone->getUser();

            
             if ($user==null) {
                throw new NotFoundHttpException("Page not found");  
            }
            
            $tokens[]=$user->getToken();
        
            $message = array(
                        "type"=>"ringtone",
                        "id"=>$ringtone->getId(),
                        "title"=> $data["title"],
                        "message"=>$data["message"],
                        "icon"=>$data["icon"]
                        );
            $key=$this->container->getParameter('fire_base_key');

             $message_status = $this->send_notificationToken($tokens, $message,$key); 
            
             $this->addFlash('success', 'Operation has been done successfully ');
            return $this->redirect($this->generateUrl('app_ringtone_index'));

        }else{
           $ringtone= $em->getRepository("AppBundle:Ringtone")->find($ringtone_id);
        }
        return $this->render('AppBundle:Home:notif_user.html.twig',array(
          "form"=>$form->createView(),
          'ringtone'=>$ringtone
        ));
    }
    
    public function indexAction()
    {   
        $em=$this->getDoctrine()->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        $supports= $em->getRepository("AppBundle:Support")->findAll();
        $supports_count= sizeof($supports);
	unset($supports);
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(dev.id)');
	$qb->from('AppBundle:Device', 'dev');
	$qb->getQuery()->useQueryCache(false);
	$devices_count = $qb->getQuery()->getSingleScalarResult();
	
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(rng.id)');
	$qb->from('AppBundle:Ringtone', 'rng');
	$qb->getQuery()->useQueryCache(false);
	$ringtone_count = $qb->getQuery()->getSingleScalarResult();
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(usr.id)');
	$qb->from('UserBundle:User', 'usr');
	$qb->getQuery()->useQueryCache(false);
	$users_count = $qb->getQuery()->getSingleScalarResult();
	
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(rpt.id)');
	$qb->from('AppBundle:Report', 'rpt');
	$qb->getQuery()->useQueryCache(false);
	$reports_count = $qb->getQuery()->getSingleScalarResult();
	
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(ctg.id)');
	$qb->from('AppBundle:Category', 'ctg');
	$qb->getQuery()->useQueryCache(false);
	$category_count = $qb->getQuery()->getSingleScalarResult();
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(rvw.id)');
	$qb->where('rvw.review = true');
	$qb->from('AppBundle:Ringtone', 'rvw');
	$qb->getQuery()->useQueryCache(false);
	$review_count = $qb->getQuery()->getSingleScalarResult();
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(sec.id)');
	$qb->from('AppBundle:Section', 'sec');
	$qb->getQuery()->useQueryCache(false);
	$section_count = $qb->getQuery()->getSingleScalarResult();
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(rpt.id)');
	$qb->from('AppBundle:Slide', 'rpt');
	$qb->getQuery()->useQueryCache(false);
	$slide_count = $qb->getQuery()->getSingleScalarResult();
	
	$qb = $em->createQueryBuilder();
	$qb->select('count(rpt.id)');
	$qb->from('AppBundle:Tag', 'rpt');
	$qb->getQuery()->useQueryCache(false);
	$tags_count = $qb->getQuery()->getSingleScalarResult();
	
	
        return $this->render('AppBundle:Home:index.html.twig',array(
            
                "supports_count"=>$supports_count,
                "devices_count"=>$devices_count,
                "reports_count"=>$reports_count,
                "ringtone_count"=>$ringtone_count,
                "category_count"=>$category_count,
                "review_count"=>$review_count,
                "users_count"=>$users_count,
                "section_count"=>$section_count,
                "slide_count"=>$slide_count,
                "tags_count"=>$tags_count

        ));
    }
    public function api_deviceAction($tkn,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $code="200";
        $message="";
        $errors=array();
        $em = $this->getDoctrine()->getManager();
        $d=$em->getRepository('AppBundle:Device')->findOneBy(array("token"=>$tkn));
        if ($d==null) {
            $device = new Device();
            $device->setToken($tkn);
            $em->persist($device);
            $em->flush();
            $message="Deivce added";
        }else{
            $message="Deivce Exist";
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
    public function tagsAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
         $q="  ";
        if ($request->query->has("q") and $request->query->get("q")!="") {
           $q.=" AND  w.title like '%".$request->query->get("q")."%'";
        }
        $dql        = "SELECT t FROM AppBundle:Tag t ORDER BY t.search desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $tags = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            12
        );
        $tags_list=$em->getRepository('AppBundle:Tag')->findAll();
        $tags_count= sizeof($tags_list);
        return $this->render('AppBundle:Home:tags.html.twig',array("tags"=>$tags,"tags_count"=>$tags_count));    
    }

    public function deletetagAction(Request $request,$id)
    {
        $em         = $this->getDoctrine()->getManager();
        $support    = $em->getRepository('AppBundle:Tag')->find($id);
        if ($support==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form=$this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('Yes', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->remove($support);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_home_tags_index'));
        }
        return $this->render("AppBundle:Home:delete_tag.html.twig",array("form"=>$form->createView()));
    }
}