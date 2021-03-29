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

foreach ($wallpapers as $key => $wallpaper) {

	$a["id"]=$wallpaper->getId();
	$a["title"]=$wallpaper->getTitle();
	$a["size"]=$wallpaper->getSize();
	$a["review"]=$wallpaper->getReview();
	$a["downloads"]=$wallpaper->getDownloads();
	$a["user"]=$wallpaper->getUserName();
	$a["userid"]=$wallpaper->getUserID();
	$a["description"]=$wallpaper->getDescription();
	$a["tags"]=$wallpaper->getTags();
	$a["rating"]=$wallpaper->getRating();
	$a["type"]=$wallpaper->getMedia()->getType();
	$a["extension"]=$wallpaper->getMedia()->getExtension();
	$a["wallpaper"] =$app->getRequest()->getSchemeAndHttpHost()."/".$wallpaper->getMedia()->getLink();
	if ($wallpaper->getUserImage()!="") {
		$a["userimage"]=$wallpaper->getUserImage();
	}else{
		$a["userimage"]= $this['imagine']->filter($view['assets']->getUrl("avatar.png"), 'user_image');

	}
	$a["created"]=$view['time']->diff($wallpaper->getCreated());
	$list[]=$a;
}
echo json_encode($list, JSON_UNESCAPED_UNICODE);
?>