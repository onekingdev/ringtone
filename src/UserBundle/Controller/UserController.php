<?php 
namespace UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Form\Model\ChangePassword;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use UserBundle\Form\UserType;
use MediaBundle\Entity\Media as Media;

class UserController extends Controller
{


    public function commentAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }

        $dql        = "SELECT c FROM AppBundle:Comment c  WHERE c.user = ".$user->getId();
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render(
                'UserBundle:User:comment.html.twig',array(
                    'pagination' => $pagination,
                    'user'=>$user
                )
                );
    }

    public function ringtonesAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }

      
        $dql        = "SELECT w FROM AppBundle:Ringtone w  WHERE w.user = ".$user->getId() ." ORDER BY w.created desc";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render(
                'UserBundle:User:ringtones.html.twig',array(
                    "ringtones"=>$pagination,
                    "user"=>$user
                )
            );
    }
    public function followersAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render(
                'UserBundle:User:followers.html.twig',array(
                    "user"=>$user
                )
            );
    }
    public function followingsAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }
        return $this->render(
                'UserBundle:User:followings.html.twig',array(
                    "user"=>$user
                )
            );
    }
    public function commentsAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }

        $dql        = "SELECT c FROM AppBundle:Comment c  WHERE c.user = ".$user->getId();
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
                'UserBundle:User:comments.html.twig',array(
                    'user'=>$user,
                    "pagination"=>$pagination,
                )
                );
    }
    public function ratingsAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }

        $dql        = "SELECT r FROM AppBundle:Rate r  WHERE r.user = ".$user->getId();
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
                'UserBundle:User:ratings.html.twig',array(
                    'user'=>$user,
                    "pagination"=>$pagination,
                    )
                );
    }
    public function editAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if ($user==null) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }


        $ringtones=$em->getRepository('AppBundle:Ringtone')->findBy(array("user"=>$user,"enabled"=>true),array("created"=>"desc"));

        $form = $this->createForm(new UserType(),$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','Operation has been done successfully');
           return $this->redirect($this->generateUrl('user_user_index'));
        }
        return $this->render(
                'UserBundle:User:edit.html.twig',array(
                    "form"=>$form->createView(),
                    'user'=>$user,
                    "ringtones"=>$ringtones,
                )
                );
    }
  
    public function deleteAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository("UserBundle:User")->find($id);
        if($user==null){
            throw new NotFoundHttpException("Page not found");
        }
        $form=$this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('Yes', 'submit',array("label"=>"Yes , delete !"))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Operation has been done successfully');
            return $this->redirect($this->generateUrl('user_user_index'));
        }
        return $this->render('UserBundle:User:delete.html.twig',array("form"=>$form->createView()));
    }

    public function indexAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $users = $em->getRepository("UserBundle:User")->findAll();

        $q=" AND ( 1=1 ) ";
        if ($request->query->has("q") and $request->query->get("q")!="") {
           $q.=" AND ( u.name like '%".$request->query->get("q")."%' or u.username like '%".$request->query->get("q")."%') ";
        }
        $dql        = "SELECT u FROM UserBundle:User u  WHERE (NOT u.roles LIKE '%ROLE_ADMIN%')   " .$q ." ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render("UserBundle:User:index.html.twig",array(
            'pagination' => $pagination,
            "users"=>$users
        ));
    }
    public function api_getAction(Request $request,$user,$me,$token){
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $code=200;
        $message="";
        $values=array();



        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->find($user);
        if($user==null){
            throw new NotFoundHttpException("Page not found");  
        }

        if ($me!=-1) {
            $me=$em->getRepository('UserBundle:User')->find($me);
            if ($me) {
                $followers = $user->getFollowers();
                $exists = false;
                foreach ($followers as $key => $f) {
                   if ($f->getId() == $me->getId()) {
                       $exists=true;
                   }
                }
                if ($exists) {
                   $values[]=array("name"=>"follow","value"=>"true");
                }else{
                   $values[]=array("name"=>"follow","value"=>"false");
                }
            }else{
                $values[]=array("name"=>"follow","value"=>"false");
            }         
        }else{
            $values[]=array("name"=>"follow","value"=>"false");
        }
        $ringtones=$user->getRingtones();
        $followers=$user->getFollowers();
        $followings=$user->getUsers();
        $wallpapers=$user->getWallpapers();

        $values[]=array("name"=>"ringtones","value"=>sizeof($ringtones));
        $values[]=array("name"=>"followers","value"=>sizeof($followers));
        $values[]=array("name"=>"followings","value"=>sizeof($followings));
        $values[]=array("name"=>"wallpapers","value"=>sizeof($wallpapers));

        $value=array(
                "code"=>$code,
                "message"=>$message,
                "values"=>$values
                );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($value, 'json');
        return new Response($jsonContent);

    }
 
    public function  api_followingsAction(Request $request,$user,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->find($user);
        if($user==null){
            throw new NotFoundHttpException("Page not found");  
        }
        $users=array();
        foreach ($user->getUsers() as $key => $e) {
           $b["id"] = $e->getId();
           $b["name"] = $e->getName();
           $b["image"] = $e->getImage();
           $users[]= $b;
        }
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($users, 'json');
        return new Response($jsonContent);  

    }
    public function api_followersAction(Request $request,$user,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->find($user);
        if($user==null){
            throw new NotFoundHttpException("Page not found");  
        }
        $followers=array();
        foreach ($user->getFollowers() as $key => $f) {
           $a["id"] = $f->getId();
           $a["name"] = $f->getName();
           $a["image"] = $f->getImage();
           $followers[]= $a;
        }
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($followers, 'json');
        return new Response($jsonContent);  

    }
    public function api_followAction(Request $request,$user,$follower,$key_,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $code=200;
        $message="";
        $errors=array();

        $em = $this->getDoctrine()->getManager();

        $user=$em->getRepository('UserBundle:User')->find($user);
        $follower=$em->getRepository('UserBundle:User')->find($follower);

        if ($user!=null and $follower!=null) {
            $followers = $user->getFollowers();
            $exists = false;
            foreach ($followers as $key => $f) {
               if ($f->getId() == $follower->getId()) {
                   $exists=true;
               }
            }
            if (sha1($follower->getPassword()) == $key_) {
                if ($exists) {
                    $user->removeFollower($follower);
                    $em->flush();
                    $code=202;  
                   $message="You Unfollowing ".$user->getName(); 
                }else{
                    $user->addFollower($follower);
                    $em->flush();
                    $code=200;  
                    $message="You following ".$user->getName(); 
                }
            }else{
                $code=500;  
                $message="Request denied please check data usage (IK)";  
            }

        }else{
            $code=500;  
            $message="Request denied please check data usage (NU)";  
        }
        $error=array(
        "code"=>$code,
        "message"=>$message,
        "values"=>array()
        );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }
    public function api_follow_checkAction(Request $request,$user,$follower,$key_,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $code=200;
        $message="";
        $errors=array();

        $em = $this->getDoctrine()->getManager();

        $user=$em->getRepository('UserBundle:User')->find($user);
        $follower=$em->getRepository('UserBundle:User')->find($follower);

        if ($user!=null and $follower!=null) {
            $followers = $user->getFollowers();
            $exists = false;
            foreach ($followers as $key => $f) {
               if ($f->getId() == $follower->getId()) {
                   $exists=true;
               }
            }
            if (sha1($follower->getPassword()) == $key_) {
                if ($exists) {
                   $code=200;  
                   $message="You Following ".$user->getName(); 
                }else{
                    $code=202;  
                    $message="You Unfollowing ".$user->getName(); 
                }
            }else{
                $code=500;  
                $message="Request denied please check data usage (IK)";  
            }

        }else{
            $code=500;  
            $message="Request denied please check data usage (NU)";  
        }
        $error=array(
        "code"=>$code,
        "message"=>$message,
        "values"=>array()
        );
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }
    public function api_loginAction($username,$password,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $code="200";
        $message="";
        $errors=array();
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("username"=>$username));

        if ($user) {
                  $encoder_service = $this->get('security.encoder_factory');
                  $encoder = $encoder_service->getEncoder($user);
                  if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt()) and !$user->hasRole("ROLE_ADMIN")) {
                    if ($user->isEnabled()==true) {
                        $code=200;  
                        $message="You have successfully logged in";
                        $errors[]=array("name"=>"id","value"=>$user->getId());
                        $errors[]=array("name"=>"name","value"=>$user->getName());
                        $errors[]=array("name"=>"type","value"=>$user->getType());
                        $errors[]=array("name"=>"username","value"=>$user->getUsername());
                        $errors[]=array("name"=>"salt","value"=>$user->getSalt());
                        $errors[]=array("name"=>"token","value"=>sha1($user->getPassword()));
                        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
                        if ($user->getMedia()==null) {
                            $errors[]=array("name"=>"url","value"=>$imagineCacheManager->getBrowserPath("img/default_male.png", 'profile_picture'));        
                        }else{
                            $errors[]=array("name"=>"url","value"=>$imagineCacheManager->getBrowserPath($user->getMedia()->getLink(), 'profile_picture'));        
                        }
                    }else{
                        $message="Your account has been disabled by an administrator";
                        $code=500;
                    }
                  }else {
                        $code=500;  
                        $message="Invalid email address or password ";
                  }

        }else{
            $code=500;  
            $message="Invalid email address or password ";
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
    public function api_editAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $email=$request->get("email");
        $facebook=$request->get("facebook");
        $instagram=$request->get("instagram");
        $twitter=$request->get("twitter");
        $name=$request->get("name");
        $user=$request->get("user");
        $key=$request->get("key");

        $code="200";
        $message="";
        $errors=array();

        $em = $this->getDoctrine()->getManager();

        $user=$em->getRepository('UserBundle:User')->find($user);

        if (!$user) {
            throw new NotFoundHttpException("Page not found");  
        }
        if (sha1($user->getPassword()) == $key) {
                $user->setFacebook($facebook);
                $user->setTwitter($twitter);
                $user->setInstagram($instagram);
                $user->setEmailo($email);
                $user->setName($name);
                $em->flush();
                $code=200;  
                $message="Your infos has been successfully edit";
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
     public function api_tokenAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $token_f=$request->get("token_f");;
        $user=$request->get("user");
        $key=$request->get("key");

        $code="200";
        $message="";
        $errors=array();

        $em = $this->getDoctrine()->getManager();

        $user=$em->getRepository('UserBundle:User')->find($user);

        if (!$user) {
            throw new NotFoundHttpException("Page not found");  
        }
        if (sha1($user->getPassword()) == $key) {
                $user->setToken($token_f);
                $em->flush();
                $code=200;  
                $message="Your infos has been successfully edit";
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
    public function api_registerAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
    	$username=$request->get("username");
    	$password=$request->get("password");
    	$name=$request->get("name");
        $type=$request->get("type");
    	$image=$request->get("image");

    	$code="200";
    	$message="";
    	$errors=array();
    	$em = $this->getDoctrine()->getManager();
    	$u=$em->getRepository('UserBundle:User')->findOneByUsername($username);
    	if ($u!=null) {
    			if ($u->getType()=="email") {
    				$code=500;
    				$message="this email address already exists";
    				$errors[]=array("name"=>"username","value"=>"this email address already exists");
    			}else{
    				$code=200;
		    		$message="You have successfully logged in";
                    $u->setImage($image);
                    $em->flush();
    				$errors[]=array("name"=>"id","value"=>$u->getId());
	                $errors[]=array("name"=>"name","value"=>$u->getName());
	                $errors[]=array("name"=>"username","value"=>$u->getUsername());
	                $errors[]=array("name"=>"salt","value"=>$u->getSalt());
	                $errors[]=array("name"=>"type","value"=>$u->getType());
	                $errors[]=array("name"=>"token","value"=>sha1($u->getPassword()));
                    $errors[]=array("name"=>"url","value"=>$u->getImage());        
                    $errors[]=array("name"=>"enabled","value"=>$u->isEnabled());        
                }
    	}else{
	    	$user = new User();
			if (count($errors)==0) {
				$user->setUsername($username);
		    	$user->setPlainPassword($password);
		    	$user->setEmail($username);
		    	$user->setEnabled(true);
		    	$user->setName($name);
                $user->setType($type);
		    	$user->setImage($image);
		    	$em->persist($user);
		    	$em->flush();
		    	$code=200;
		    	$message="You have successfully registered";
                $errors[]=array("name"=>"id","value"=>$user->getId());
                $errors[]=array("name"=>"name","value"=>$user->getName());
                $errors[]=array("name"=>"username","value"=>$user->getUsername());
                $errors[]=array("name"=>"salt","value"=>$user->getSalt());
                $errors[]=array("name"=>"type","value"=>$user->getType());
                $errors[]=array("name"=>"token","value"=>sha1($user->getPassword()));
                $errors[]=array("name"=>"url","value"=>$user->getImage());   
                $errors[]=array("name"=>"enabled","value"=>$user->isEnabled());             
            }else{
				$code=500;
    			$message="validation error";
			}
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
    public function api_change_passwordAction($id,$password,$new_password,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        sleep(2);
        $code="200";
        $message="";
        $errors=array();
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("id"=>$id));  
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user->getType()!="email") {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user) {
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {

                if (strlen($new_password)<6) {
                    $code=500;
                    $errors["password"]="cette valeur est trop courte";
                }else{
                    $newPasswordEncoded = $encoder->encodePassword($new_password, $user->getSalt());
                    $user->setPassword($newPasswordEncoded);
                    $em->persist($user);
                    $em->flush();
                    $code=200;
                    $message="Password has been changed successfully";
                        $errors[]=array("name"=>"id","value"=>$user->getId());
                        $errors[]=array("name"=>"name","value"=>$user->getName());
                        $errors[]=array("name"=>"type","value"=>$user->getType());
                        $errors[]=array("name"=>"username","value"=>$user->getUsername());
                        $errors[]=array("name"=>"salt","value"=>$user->getSalt());
                        $errors[]=array("name"=>"token","value"=>sha1($user->getPassword()));
                }
            } else {
                $code=500;  
                $message="Current password is incorrect";
            }
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

    public function api_edit_nameAction($id,$name,$key,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }

        $code="200";
        $message="";
        $errors=array();
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("id"=>$id));  
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }
        if (sha1($user->getPassword()) != $key) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user) {
            $user->setName($name);
            $em->flush();
            $message="Your information has been edit ";
            $code="200";
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
    public function api_checkAction($id,$key,$token)
    {
        $code="500";
        $message="";
        $errors=array();
        if ($token!=$this->container->getParameter('token_app')) {
            $code=500;
        }

        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("id"=>$id));

        if($user){
            if ($user->isEnabled()) {
                if ($key==sha1($user->getPassword())) {
                    $code=200;
                }else{
                    $code=500;
                }
            }else{
                $code=500;
            }
            if ($user->hasRole("ROLE_ADMIN")) {
                $code=500;
            }
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
    public function api_uploadAction(Request $request,$id,$key,$token)
    {
        $code="200";
        $message="Ok";
        $values=array();
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('UserBundle:User')->findOneBy(array("id"=>$id));  
        if ($user->hasRole("ROLE_ADMIN")) {
            throw new NotFoundHttpException("Page not found");
        }
        if (sha1($user->getPassword()) != $key) {
            throw new NotFoundHttpException("Page not found");
        }
        if ($user) {        
            if ($this->getRequest()->files->has('uploaded_file')) {
                $old_media=$user->getMedia();
                $media= new Media();
                $media->setFile($this->getRequest()->files->get('uploaded_file'));
                $media->upload($this->container->getParameter('files_directory'));
                $media->setEnabled(true);
                $em->persist($media);
                $em->flush();
                $user->setMedia($media);
                if($old_media!=null){
                        $old_media->delete($this->container->getParameter('files_directory'));
                        $em->remove($old_media);
                        $em->flush();
                }
                $em->flush();   
                $imagineCacheManager = $this->get('liip_imagine.cache.manager');
                $values[]=array("name"=>"url","value"=>$imagineCacheManager->getBrowserPath($media->getLink(), 'profile_picture'));
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
} 
