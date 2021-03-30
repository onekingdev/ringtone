<?php 
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Ringtone;
use MediaBundle\Entity\Media;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Rate;
use AppBundle\Entity\MP3File;
use AppBundle\Form\RingtoneMultiType;
use AppBundle\Form\RingtoneType;
use AppBundle\Form\RingtoneEditType;
use AppBundle\Form\RingtoneReviewType;
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
class RingtoneController extends Controller
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
        $ringtone= new Ringtone();
        $em=$this->getDoctrine()->getManager();
        $form = $this->createForm(new RingtoneType($em),$ringtone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	 if( $ringtone->getFile()!=null ){
                $file = $ringtone->getFile();
                $media= new Media();
                $media->setFile($file);
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $size = $this->format_size($file->getClientSize());
                $ringtone->setUser($this->getUser());
          			$ringtone->setDownloads(0);
                $ringtone->setSize($size);
        				$ringtone->setMedia($media);
                if ($media->getExtension()=="mp3") {
                  @$mp3file = new MP3File($this->container->getParameter('files_directory').$media->getUrl());
                  @$ringtone->setDuration($mp3file->getDuration());
                }else{
                   @$ringtone->setDuration($this->wavDur($this->container->getParameter('files_directory').$media->getUrl()));
                }
                $tags_list = explode(",", $ringtone->getTags());
                foreach ($tags_list as $key => $value) {
                  $tag =  $em->getRepository("AppBundle:Tag")->findOneBy(array("name"=>strtolower($value)));
                  if ($tag ==null) {
                    $tag = new Tag();
                    $tag->setName(strtolower($value));
                    $em->persist($tag);
                    $em->flush();
                    $ringtone->addTagslist($tag);
                  }else{
                    $ringtone->addTagslist($tag);
                  }
                }


  	            $em->persist($ringtone);
  	            $em->flush();
                      
                $this->addFlash('success', 'Operation has been done successfully');
                return $this->redirect($this->generateUrl('app_ringtone_index'));
            }else{
                $error = new FormError("Required image file");
                $form->get('file')->addError($error);
            }
 		     }
        return $this->render("AppBundle:Ringtone:add.html.twig",array("form"=>$form->createView()));
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
        $dql        = "SELECT w FROM AppBundle:Ringtone w  WHERE w.review = false ".$q ." ORDER BY w.created desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $ringtones = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            12
        );
        
        $ringtones_list=$em->getRepository('AppBundle:Ringtone')->findAll();
	      $ringtones_count= sizeof($ringtones_list);
	      return $this->render('AppBundle:Ringtone:index.html.twig',array("ringtones"=>$ringtones,"ringtones_count"=>$ringtones_count, "searchText"=>$searchText));    
    }

    public function reviewsAction(Request $request)
    {

      $em=$this->getDoctrine()->getManager();
      
        $dql        = "SELECT w FROM AppBundle:Ringtone w  WHERE w.review = true ORDER BY w.created desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $ringtones = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            12
        );
        $ringtones_list=$em->getRepository('AppBundle:Ringtone')->findBy(array("review"=>true));
        $ringtones_count= sizeof($ringtones_list);
        return $this->render('AppBundle:Ringtone:reviews.html.twig',array("ringtones"=>$ringtones,"ringtones_count"=>$ringtones_count));    
    }
    public function deleteAction($id,Request $request){
		$em=$this->getDoctrine()->getManager();
		$ids = null;
		
		if ($request->query->has("ids") && $request->query->get("ids") != "") {
			$ids = json_decode($request->query->get("ids"));
		}

		if ($ids === null) {
        	$ringtone = $em->getRepository("AppBundle:Ringtone")->find($id);
	        if($ringtone==null){
   		        throw new NotFoundHttpException("Page not found");
   		    }

        	$form=$this->createFormBuilder(array('id' => $id))
            	->add('id', 'hidden')
            	->add('Yes', 'submit')
            	->getForm();
        	$form->handleRequest($request);
       	 	if($form->isSubmitted() && $form->isValid()) {
            	$report = $em->getRepository("AppBundle:Report")->findOneBy(array("ringtone"=>$ringtone));
            	if ($report!=null) {
                	$em->remove($report);
                	$em->flush();
            	}
	        	if( $ringtone->getMedia()!=null ){
	                $media_old=$ringtone->getMedia();
	                $media_old->delete($this->container->getParameter('files_directory'));
	                $em->remove($ringtone);
                  $em->flush();
	          	}

            	$em->remove($media_old);
            	$em->flush();
            	$this->addFlash('success', 'Operation has been done successfully');
            	return $this->redirect($this->generateUrl('app_ringtone_index'));
       		} 
		} else {
        	$form=$this->createFormBuilder(array('id' => $ids[0]))
            	->add('id', 'hidden')
            	->add('Yes', 'submit')
            	->getForm();
        	$form->handleRequest($request);
			if($form->isSubmitted() && $form->isValid()) {
				foreach ($ids as $id) {
        			$ringtone = $em->getRepository("AppBundle:Ringtone")->find($id);
        			if($ringtone==null){
            			throw new NotFoundHttpException("Page not found");
        			}

            		$report = $em->getRepository("AppBundle:Report")->findOneBy(array("ringtone"=>$ringtone));
            		if ($report!=null) {
                		$em->remove($report);
                		$em->flush();
            		}

	        		if( $ringtone->getMedia()!=null ){
	                	$media_old=$ringtone->getMedia();
	                	$media_old->delete($this->container->getParameter('files_directory'));
	                	$em->remove($ringtone);
                  		$em->flush();
	          		}

					$em->remove($media_old);
					$em->flush();
				}

           	 	$this->addFlash('success', 'Operation has been done successfully');
            	return $this->redirect($this->generateUrl('app_ringtone_index'));
			}
		}
        return $this->render('AppBundle:Ringtone:delete.html.twig',array("form"=>$form->createView()));
    }
    
    public function editAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->findOneBy(array("id"=>$id,"review"=>false));
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $tags="";
        foreach ($ringtone->getTagslist() as $key => $value) {
          if ($key == sizeof($ringtone->getTagslist())-1) {
            $tags.=$value->getName();
          }else{
            $tags.=$value->getName().",";
          }
        }
        $ringtone->setTags($tags);


        $form = $this->createForm(new RingtoneType($em),$ringtone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	  $ringtone->setTagslist(array());
            $em->persist($ringtone);
            $em->flush();


            $tags_list = explode(",", $ringtone->getTags());
            foreach ($tags_list as $k => $v) {
              $tags_list[$k]=strtolower($v);
            }
            $tags_list = array_unique($tags_list);

            foreach ($tags_list as $key => $value) {
                $tag =  $em->getRepository("AppBundle:Tag")->findOneBy(array("name"=>strtolower($value)));
                if ($tag ==null) {
                  $tag = new Tag();
                  $tag->setName(strtolower($value));
                  $em->persist($tag);
                  $em->flush();
                  $ringtone->addTagslist($tag);
                }else{
                  $ringtone->addTagslist($tag);
                }
            }


          if( $ringtone->getFile()!=null ){
                $media= new Media();
                $media_old=$ringtone->getMedia();
                $media->setFile($ringtone->getFile());
                $media->setEnabled(true);
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $ringtone->setMedia($media);
                $em->flush();
                $media_old->delete($this->container->getParameter('files_directory'));
                $em->remove($media_old);
                $em->flush();
                $size = $this->format_size($ringtone->getFile()->getClientSize());
                $ringtone->setSize($size);
                if ($media->getExtension()=="mp3") {
                  @$mp3file = new MP3File($this->container->getParameter('files_directory').$media->getUrl());
                  @$ringtone->setDuration($mp3file->getDuration());
                }else{
                   @$ringtone->setDuration($this->wavDur($this->container->getParameter('files_directory').$media->getUrl()));
                }
            }
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_ringtone_index'));
        }
        return $this->render("AppBundle:Ringtone:edit.html.twig",array("form"=>$form->createView()));
    }
    public function reviewAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->findOneBy(array("id"=>$id,"review"=>true));
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form = $this->createForm(new RingtoneReviewType(),$ringtone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ringtone->setReview(false);
            $ringtone->setEnabled(true);
            $ringtone->setCreated(new \DateTime());
            $em->persist($ringtone);
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_home_notif_user',array("ringtone_id"=>$ringtone->getId())));
        }
        return $this->render("AppBundle:Ringtone:review.html.twig",array("form"=>$form->createView()));
    }
     public function viewAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->find($id);
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");
        }

        $rates_1 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone,"value"=>1));
        $rates_2 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone,"value"=>2));
        $rates_3 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone,"value"=>3));
        $rates_4 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone,"value"=>4));
        $rates_5 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone,"value"=>5));
        $rates = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$ringtone));


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
        return $this->render("AppBundle:Ringtone:view.html.twig",array("ringtone"=>$ringtone,"rating"=>$rating,"ratings"=>$ratings,"values"=>$values));
    }
    public function api_getAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->findOneBy(array("id"=>$id,"enabled"=>true));
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render("AppBundle:Ringtone:api_get.html.php",array("ringtone"=>$ringtone));
    }
    public function api_allAction(Request $request,$page,$order,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Ringtone');
        $query = $repository->createQueryBuilder('w')
        ->where("w.enabled = true")
        ->addOrderBy('w.'.$order, 'DESC')
        ->addOrderBy('w.id', 'asc')
        ->setFirstResult($nombre*$page)
        ->setMaxResults($nombre)
        ->getQuery();
        $ringtones = $query->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    public function api_relatedAction(Request $request,$id,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }

        $nombre=30;
        
        $em=$this->getDoctrine()->getManager();

        $ringtone = $em->getRepository('AppBundle:Ringtone')->findOneBy(array("enabled"=>true,"id"=>$id));
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");  
        }
        $categories="";
        foreach ($ringtone->getCategories() as $key => $category) {
           $categories=$categories.",".$category->getId();
        }


            $imagineCacheManager = $this->get('liip_imagine.cache.manager');
            $repository = $em->getRepository('AppBundle:Ringtone');

        if ($categories=="") {
            $query = $repository->createQueryBuilder('w')
            ->where("w.enabled = true","w.id != :ringtone")
            ->setParameter('ringtone', $ringtone)
            ->addOrderBy('w.downloads', 'DESC')
            ->addOrderBy('w.id', 'asc')
            ->setFirstResult(0)
            ->setMaxResults($nombre)
            ->getQuery(); 
        }else{

            $categories = trim($categories,",");

            $query = $repository->createQueryBuilder('w')
            ->leftJoin('w.categories', 'c')
            ->where('c.id in ('.$categories.')',"w.enabled = true","w.id != :ringtone")
            ->setParameter('ringtone', $ringtone)
            ->addOrderBy('w.downloads', 'DESC')
            ->addOrderBy('w.id', 'asc')
            ->setFirstResult(0)
            ->setMaxResults($nombre)
            ->getQuery();
        }
        $ringtones = $query->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    public function api_by_categoryAction(Request $request,$page,$category,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Ringtone');
        
        $query = $repository->createQueryBuilder('w')
          ->leftJoin('w.categories', 'c')
          ->where('c.id = :category',"w.enabled = true")
          ->setParameter('category', $category)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        
        $ringtones = $query->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    public function api_by_userAction(Request $request,$page,$user,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Ringtone');
        
        $query = $repository->createQueryBuilder('w')
          ->where('w.user = :user',"w.enabled = true")
          ->setParameter('user', $user)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        $ringtones = $query->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    public function api_by_meAction(Request $request,$page,$user,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Ringtone');
        
        $query = $repository->createQueryBuilder('w')
          ->where('w.user = :user')
          ->setParameter('user', $user)
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        $ringtones = $query->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    
    public function api_by_queryAction(Request $request,$page,$query,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $nombre=30;
        $em=$this->getDoctrine()->getManager();
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $repository = $em->getRepository('AppBundle:Ringtone');
        
        $tags_list=explode(" ", $query);
        foreach ($tags_list as $key => $value) {
           $tag = $em->getRepository("AppBundle:Tag")->findOneBy(array("name"=>$value));
           if ($tag!=null) {
             $tag->setSearch($tag->getSearch()+1);
             $em->flush();           
           }

        }
        $query_dql = $repository->createQueryBuilder('w')
          ->where("w.enabled = true","LOWER(w.title) like LOWER('%".$query."%') OR LOWER(w.tags) like LOWER('%".$query."%') ")
          ->addOrderBy('w.created', 'DESC')
          ->addOrderBy('w.id', 'asc')
          ->setFirstResult($nombre*$page)
          ->setMaxResults($nombre)
          ->getQuery();
        
        $ringtones = $query_dql->getResult();
        return $this->render('AppBundle:Ringtone:api_first.html.php',array("ringtones"=>$ringtones));
    }
    public function api_add_downloadAction(Request $request,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $id = $request->get("id");        
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->find($id);
        $ringtone->setDownloads($ringtone->getDownloads()+1);
        $em->flush();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($ringtone->getDownloads(), 'json');
        return new Response($jsonContent);
    }
    public function api_add_setAction(Request $request,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $id = $request->get("id");        
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->find($id);
        $ringtone->setSets($ringtone->getSets()+1);
        $em->flush();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($ringtone->getDownloads(), 'json');
        return new Response($jsonContent);
    }


    public function api_add_rateAction($user,$ringtone,$value,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $a=$em->getRepository('AppBundle:Ringtone')->find($ringtone);
        $u=$em->getRepository("UserBundle:User")->find($user);
        $code="200";
        $message="";
        $errors=array();

        if ($u!=null and $a!=null) {

            $rate =  $em->getRepository('AppBundle:Rate')->findOneBy(array("user"=>$u,"ringtone"=>$a));
            if ($rate == null) {
                $rate_obj = new Rate();
                $rate_obj->setValue($value);
                $rate_obj->setRingtone($a);
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
    public function api_get_rateAction($user=null,$ringtone,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $a=$em->getRepository('AppBundle:Ringtone')->find($ringtone);
        $u=$em->getRepository("UserBundle:User")->find($user);
        $code="200";
        $message="";
        $errors=array();

        if ($a!=null) {
            $rates_1 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a,"value"=>1));
            $rates_2 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a,"value"=>2));
            $rates_3 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a,"value"=>3));
            $rates_4 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a,"value"=>4));
            $rates_5 = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a,"value"=>5));
            $rates = $em->getRepository('AppBundle:Rate')->findBy(array("ringtone"=>$a));
            $rate = null;
            if ($u!=null) {
                $rate =  $em->getRepository('AppBundle:Rate')->findOneBy(array("user"=>$u,"ringtone"=>$a));
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
        $duration=$request->get("duration");
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


                $media= new Media();
                $media->setFile($file);
                $media->upload($this->container->getParameter('files_directory'));
                $em->persist($media);
                $em->flush();
                $size = $this->format_size($file->getClientSize());
                $url  = $this->container->getParameter('files_directory').$media->getUrl();
                $w= new Ringtone();
                $w->setDownloads(0);


                foreach ($categories_list as $key => $id_category) {
                  $category_obj = $em->getRepository('AppBundle:Category')->find($id_category); 
                  if ($category_obj) {
                      $w->addCategory($category_obj);
                  }
                }
                $w->setEnabled(false);
                $w->setReview(true);
                $w->setTitle($title);
                $w->setUser($user);
                $w->setSize($size);
                $w->setMedia($media);
                $w->setDuration(($duration/1000));
                $em->persist($w);
                $em->flush();








               /* $user->setMedia($media);
                if($old_media!=null){
                        $old_media->remove($this->container->getParameter('files_directory'));
                        $em->remove($old_media);
                        $em->flush();
                }
                $em->flush();   
                $imagineCacheManager = $this->get('liip_imagine.cache.manager');
                $values[]=array("name"=>"url","value"=>$imagineCacheManager->getBrowserPath($media->getLink(), 'profile_picture'));
                */
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
        $ringtone= new Ringtone();
        $form = $this->createForm(new RingtoneMultiType(),$ringtone);
        $em=$this->getDoctrine()->getManager();
        $colors=$em->getRepository('AppBundle:Color')->findBy(array(),array("position"=>"asc"));
        $ringtone->setTitle("new Ringtone");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           if( $ringtone->getFiles()!=null ){
            $all=0;
                $valide=0;
                $pos=1;
                foreach($ringtone->getFiles() as $k => $file){
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
                        $w= new Ringtone();
                        $w->setDownloads(0);
                        $w->setCategories($ringtone->getCategories());
                        $w->setTags($ringtone->getTags());
                        $w->setDescription($ringtone->getDescription());
                        $w->setColors($ringtone->getColors());
                        $w->setComment($ringtone->getComment());
                        $w->setEnabled($ringtone->getEnabled());
                        if ($ringtone->getUsefilename()) {
                          $w->setTitle(preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName()));
                        }else{
                          $w->setTitle($ringtone->getTitlemulti());
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
                return $this->redirect($this->generateUrl('app_ringtone_index'));
            }else{
              $error = new FormError("Required image file");
              $form->get('files')->addError($error);
         }
    }
        return $this->render("AppBundle:Ringtone:multi.html.twig",array("form"=>$form->createView(),"colors"=>$colors));
    }
    public function shareAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ringtone=$em->getRepository("AppBundle:Ringtone")->find($id);
        if ($ringtone==null) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render("AppBundle:Ringtone:share.html.twig",array("ringtone"=>$ringtone));
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
