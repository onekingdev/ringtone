<?php 


	$obj["id"]=$ringtone->getId();
	$obj["title"]=$ringtone->getTitle();
	$obj["size"]=$ringtone->getSize();
	$obj["review"]=$ringtone->getReview();
	$obj["downloads"]=$ringtone->getDownloads();
	$obj["user"]=$ringtone->getUser()->getName();
	$obj["userid"]=$ringtone->getUser()->getId();
	$obj["description"]=$ringtone->getDescription();
	$obj["tags"]=$ringtone->getTags();
	$obj["rating"]=$ringtone->getRating();
	$obj["duration"]=$ringtone->getDuration();
	$obj["type"]=$ringtone->getMedia()->getType();
	$obj["extension"]=$ringtone->getMedia()->getExtension();
	$obj["ringtone"] =$app->getRequest()->getSchemeAndHttpHost()."/".$ringtone->getMedia()->getLink();
	if ($ringtone->getUser()->getImage()!="") {
		$obj["userimage"]=$ringtone->getUser()->getImage();
	}else{
		$obj["userimage"]= $this['imagine']->filter($view['assets']->getUrl("avatar.png"), 'user_image');

	}
	$obj["created"]=$view['time']->diff($ringtone->getCreated());

echo json_encode($obj, JSON_UNESCAPED_UNICODE);
?>