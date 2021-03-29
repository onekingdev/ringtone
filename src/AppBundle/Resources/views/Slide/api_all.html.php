<?php 
$list=array();
foreach ($slides as $key => $slide) {
	$s=null;
    $s["id"]=$slide->getId();
    $s["title"]=$slide->getTitle();
    $s["type"]=$slide->getType();
    $s["image"]=$this['imagine']->filter($view['assets']->getUrl($slide->getMedia()->getLink()), 'slide_thumb');
    if ($slide->getType()==3 && $slide->getRingtone()!=null)
    {

		$a["id"]=$slide->getRingtone()->getId();
		$a["title"]=$slide->getRingtone()->getTitle();
		$a["size"]=$slide->getRingtone()->getSize();
		$a["review"]=$slide->getRingtone()->getReview();
		$a["downloads"]=$slide->getRingtone()->getDownloads();
		$a["user"]=$slide->getRingtone()->getUser()->getName();
		$a["userid"]=$slide->getRingtone()->getUser()->getId();
		$a["description"]=$slide->getRingtone()->getDescription();
		$a["tags"]=$slide->getRingtone()->getTags();
		$a["rating"]=$slide->getRingtone()->getRating();
		$a["duration"]=$slide->getRingtone()->getDuration();
		$a["type"]=$slide->getRingtone()->getMedia()->getType();
		$a["extension"]=$slide->getRingtone()->getMedia()->getExtension();
		$a["ringtone"] =$app->getRequest()->getSchemeAndHttpHost()."/".$slide->getRingtone()->getMedia()->getLink();
		if ($slide->getRingtone()->getUser()->getImage()!="") {
			$a["userimage"]=$slide->getRingtone()->getUser()->getImage();
		}else{
			$a["userimage"]= $this['imagine']->filter($view['assets']->getUrl("avatar.png"), 'user_image');

		}
		$a["created"]=$view['time']->diff($slide->getRingtone()->getCreated());
		$s["ringtone"]=$a;
    }elseif($slide->getType()==1 && $slide->getCategory()!=null){
		$c["id"]=$slide->getCategory()->getId();
        $c["title"]=$slide->getCategory()->getTitle();
        $c["image"]=$this['imagine']->filter($view['assets']->getUrl($slide->getCategory()->getMedia()->getLink()), 'section_thumb_api');
		$s["category"]=$c;
	}elseif($slide->getType()==2 && $slide->getUrl()!=null){
	    $s["url"]=$slide->getUrl();
    }
    $list[]=$s;
}
echo json_encode($list, JSON_UNESCAPED_UNICODE);

 ?>