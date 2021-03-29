<?php 
function truncate($text, $length=38)
   {
      $trunc = (strlen($text)>$length)?true:false;
      if($trunc)
         return substr($text, 0, $length).'...';
      else
         return $text;
   }
$list=array();

foreach ($ringtones as $key => $ringtone) {

	$a["id"]=$ringtone->getId();
	$a["title"]=$ringtone->getTitle();
	$a["size"]=$ringtone->getSize();
	$a["review"]=$ringtone->getReview();
	$a["downloads"]=$ringtone->getDownloads();
	$a["user"]=$ringtone->getUser()->getName();
	$a["userid"]=$ringtone->getUser()->getId();
	$a["description"]=$ringtone->getDescription();
	$a["tags"]=$ringtone->getTags();
	$a["rating"]=$ringtone->getRating();
	$a["duration"]=$ringtone->getDuration();
	$a["type"]=$ringtone->getMedia()->getType();
	$a["extension"]=$ringtone->getMedia()->getExtension();
	$a["ringtone"] =$app->getRequest()->getSchemeAndHttpHost()."/".$ringtone->getMedia()->getLink();
	if ($ringtone->getUser()->getImage()!="") {
		$a["userimage"]=$ringtone->getUser()->getImage();
	}else{
		$a["userimage"]= $this['imagine']->filter($view['assets']->getUrl("avatar.png"), 'user_image');

	}
	$a["created"]=$view['time']->diff($ringtone->getCreated());
	$list[]=$a;
}
echo json_encode($list, JSON_UNESCAPED_UNICODE);
?>