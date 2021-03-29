<?php 


	$obj["id"]=$wallpaper->getId();
	$obj["title"]=$wallpaper->getTitle();
	$obj["size"]=$wallpaper->getSize();
	$obj["review"]=$wallpaper->getReview();
	$obj["downloads"]=$wallpaper->getDownloads();
	$obj["user"]=$wallpaper->getUser()->getName();
	$obj["userid"]=$wallpaper->getUser()->getId();
	$obj["description"]=$wallpaper->getDescription();
	$obj["tags"]=$wallpaper->getTags();
	$obj["rating"]=$wallpaper->getRating();
	$obj["type"]=$wallpaper->getMedia()->getType();
	$obj["extension"]=$wallpaper->getMedia()->getExtension();
	$obj["wallpaper"] =$app->getRequest()->getSchemeAndHttpHost()."/".$wallpaper->getMedia()->getLink();
	if ($wallpaper->getUserImage()!="") {
		$obj["userimage"]=$wallpaper->getUserImage();
	}else{
		$obj["userimage"]= $this['imagine']->filter($view['assets']->getUrl("avatar.png"), 'user_image');

	}
	$obj["created"]=$view['time']->diff($wallpaper->getCreated());

echo json_encode($obj, JSON_UNESCAPED_UNICODE);
?>