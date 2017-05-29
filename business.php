<?php
function get_db()
{
		$mongo = new MongoClient(
		"mongodb://localhost:27017/",
		[
			'username' => 'wai_web',
			'password' => 'w@i_w3b',
			'db' => 'wai',
		]);
	$db = $mongo->wai;
	return $db;
}

function add_photo_to_database($author, $title, $target_file, $DestinationFile, $DestinationFileThumb)
{
    $db = get_db();
    $photo = [
        'author' => $author,
        'title' => $title,
		'pathToImage' => $target_file,
		'pathToImageWatermarked' => $DestinationFile,
		'pathToImageThumbnail' => $DestinationFileThumb
    ];
    $db->photos->insert($photo);
    return $photo;
}

function get_photos_from_database(){
	$db = get_db();
    return $db->photos->find();
}

function get_saved_photos_from_database($memorized){
	$db = get_db();
	
	$result = [];
	if(isset($memorized)){
		foreach ($memorized as $memo){
			$photo = $db->photos->findOne([
				'_id' => new MongoId($memo)
			]);
			$result[] = $photo;
		}
	}
		
    return $result;
}

function watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile) { 
	list($width, $height) = getimagesize($SourceFile);
	$image_p = imagecreatetruecolor($width, $height);
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	if($imageFileType == "jpg"){
		$image = imagecreatefromjpeg($SourceFile);
	} elseif($imageFileType == "png"){
		$image = imagecreatefrompng($SourceFile);
	}
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
	$black = imagecolorallocate($image_p, 0, 0, 0);
	$font = '/var/www/dev/web/static/fonts/Aaargh.ttf';
	$font_size = 20; 
	imagettftext($image_p, $font_size, 0, 50, 50, $black, $font, $WaterMarkText);
	
	if (($DestinationFile<>'') && ($imageFileType == "jpg")){
			imagejpeg ($image_p, $DestinationFile, 100); 
		} elseif($imageFileType == "jpg") {
			header('Content-Type: image/jpeg');
			imagejpeg($image_p, null, 100);
		};

	
	if (($DestinationFile<>'') && ($imageFileType == "png")) {
			imagepng ($image_p, $DestinationFile, 9); 
		} elseif($imageFileType == "png") {
			header('Content-Type: image/png');
			imagepng($image_p, null, 9);
		};

	
	imagedestroy($image); 
	imagedestroy($image_p); 
};

function make_thumbJPG($SourceFile, $DestinationFileThumb, $desired_width, $desired_h) {

  $source_image = imagecreatefromjpeg($SourceFile);
  $width = imagesx($source_image);
  $height = imagesy($source_image);

  $desired_height = $desired_h;
  $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

  imagejpeg($virtual_image, $DestinationFileThumb);
}

function make_thumbPNG($SourceFile, $DestinationFileThumb, $desired_width, $desired_h) {

  $source_image = imagecreatefrompng($SourceFile);
  $width = imagesx($source_image);
  $height = imagesy($source_image);

  $desired_height = $desired_h;
  $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

  imagepng($virtual_image, $DestinationFileThumb);
}

function add_user($login, $password, $email)
{
    $db = get_db();
    $user = [
        'login' => $login,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'email' => $email,
    ];
    $db->users->insert($user);
}

function get_users_from_database(){
	$db = get_db();
    return $db->users->find();
}

function get_user_by_login($login) {
    $db = get_db();
    
    $user = $db->users->findOne([
        'login' => $login
    ]);
    
    return $user;
}

function check_login_in_database($login)
{
    $db = get_db();
    $user = [
        'login' => $login
    ];
    $reply = $db->users->findOne($user);
    if($reply) {
        return false;
    }
    else {
        return true;
    }
}

function is_user_logged_in()
{
    return empty($_SESSION['user']) ? false : true;
}

function check_if_checked($photo_id)
{
	if(isset($_SESSION['memorized_photos'])){
		foreach($_SESSION['memorized_photos'] as $memorized_photos){
			if($memorized_photos == $photo_id){
				return "checked";
			}
		}
	}
}

?>