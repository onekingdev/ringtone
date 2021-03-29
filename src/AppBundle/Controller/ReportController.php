<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AppBundle\Entity\Report;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportController extends Controller
{
    public function api_addAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $message = $request->get("message");
        $ringtone_id = $request->get("ringtone");
        $em         = $this->getDoctrine()->getManager();
        $ringtone    = $em->getRepository("AppBundle:Ringtone")->find($ringtone_id);


        $report    = new Report();
        $report->setRingtone($ringtone);
        $report->setMessage($message);
        $em->persist($report);
        $em->flush();
        $code="200";
        $message="Votre message a bien été envoyé";
        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>array()
        );  
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }
    
    public function api_add_wallpaperAction(Request $request,$token)
    {
        if ($token!=$this->container->getParameter('token_app')) {
            throw new NotFoundHttpException("Page not found");  
        }
        $message = $request->get("message");
        $wallpaper_id = $request->get("wallpaper");
        $em         = $this->getDoctrine()->getManager();
        $wallpaper    = $em->getRepository("AppBundle:Wallpaper")->find($wallpaper_id);


        $report    = new Report();
        $report->setWallpaper($wallpaper);
        $report->setMessage($message);
        $em->persist($report);
        $em->flush();
        $code="200";
        $message="Votre message a bien été envoyé";
        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>array()
        );  
        header('Content-Type: application/json'); 
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent=$serializer->serialize($error, 'json');
        return new Response($jsonContent);
    }
    
    
    public function indexAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $dql        = "SELECT s FROM AppBundle:Report s ORDER BY s.created desc ";
        $query      = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
            10
        );
        $reports= $em->getRepository('AppBundle:Report')->findAll();
        return $this->render('AppBundle:Report:index.html.twig',
            array(
                'pagination' => $pagination,
                "reports"=> $reports
            )
        );
    }
    public function deleteAction(Request $request,$id)
    {
        $em         = $this->getDoctrine()->getManager();
        $report    = $em->getRepository('AppBundle:Report')->find($id);
        if ($report==null) {
            throw new NotFoundHttpException("Page not found");
        }
        $form=$this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->add('Yes', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->remove($report);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success','Operation has been done successfully');
            return $this->redirect($this->generateUrl('app_report_index'));
        }
        return $this->render("AppBundle:Report:delete.html.twig",array("form"=>$form->createView()));
    }
}
