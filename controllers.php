<?php
require_once 'business.php';

function upload(&$model){
	$target_dir = "/var/www/dev/web/static/images/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	$uploadOk = 1;
if(isset($_POST["submit"]) && isset($_FILES['fileToUpload'])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $err1 = "Plik jest zdjeciem - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $err7 = "Plik nie jest zdjeciem.";
        $uploadOk = 0;
    }
}
if ($_FILES["fileToUpload"]["size"] > 1000000) {
    $err2 = "Twoj plik jest zbyt duzy (>1MB).";
    $uploadOk = 0;
}

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $err3 = "Tylko formaty JPG, JPEG i PNG sa akceptowalne.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $err4 = "Twoj plik nie zostal wrzucony.";
} 
else {
	
	$DestinationFile = '/var/www/dev/web/static/images_watermarked/'. basename($_FILES["fileToUpload"]["name"]) . '_watermarked.jpg';
	$DestinationFileThumb = '/var/www/dev/web/static/images_thumbnails/'. basename($_FILES["fileToUpload"]["name"]). '_thumbnail.jpg';
	
	$title = $_POST['title'];
	$author = $_POST['author'];
	$pathToImage = '/static/images/' . basename($_FILES["fileToUpload"]["name"]);
	$pathToImageWatermarked = '/static/images_watermarked/' . basename($_FILES["fileToUpload"]["name"]) . '_watermarked.jpg';
	$pathToImageThumbnail = '/static/images_thumbnails/'. basename($_FILES["fileToUpload"]["name"]). '_thumbnail.jpg';
	
	$model['title'] = $title;
	$model['author'] = $author;
	$model['pathToImage'] = $pathToImage;
	$model['pathToImageWatermarked'] = $pathToImageWatermarked;
	$model['pathToImageThumbnail'] = $pathToImageThumbnail;

	add_photo_to_database($author, $title, $pathToImage, $pathToImageWatermarked, $pathToImageThumbnail);
	$WaterMarkText = $_POST["watermark"];
	$SourceFile = $_FILES["fileToUpload"]["tmp_name"];
	watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile);
	if($imageFileType == "jpg"){
		make_thumbJPG ($DestinationFile, $DestinationFileThumb, 200, 125);
	}
	elseif($imageFileType == "png"){
		make_thumbPNG ($DestinationFile, $DestinationFileThumb, 200, 125);		
	}
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $err5 = "Plik ". basename( $_FILES["fileToUpload"]["name"]). " zostal wrzucony.";
    } else {
        $err6 = "Byl problem z wrzuceniem twojego pliku.";
    }
	
}
	    $messages = [
        'err1' => null,
        'err2' => null,
        'err3' => null,
        'err4' => null,
        'err5' => null,
        'err6' => null,
        'err7' => null,
    ];
	
	if(isset($err1))($messages['err1'] = $err1);
	if(isset($err2))($messages['err2'] = $err2);
	if(isset($err3))($messages['err3'] = $err3);
	if(isset($err4))($messages['err4'] = $err4);
	if(isset($err5))($messages['err5'] = $err5);
	if(isset($err6))($messages['err6'] = $err6);
	if(isset($err7))($messages['err7'] = $err7);
	
	$model['messages'] = $messages;

return 'upload_view';
}

function etykieta(&$model)
{
    return 'etykieta_view';
}

function galeria(&$model)
{
	$photos = get_photos_from_database();
    $model['photos'] = $photos;

    return 'galeria_view';
}

function galeria_zapamietane(&$model)
{
	$photos = get_saved_photos_from_database($_SESSION['memorized_photos']);
    $model['photos'] = $photos;
	
    return 'galeria_zapamietane_view';
}


function poco(&$model)
{	
	$photos = get_photos_from_database();
    $model['photos'] = $photos;
	
	$users = get_users_from_database();
	$model['users'] = $users;
	
    return 'poco_view';
}

function clean(&$model)
{
    return 'clean';
}

function zaloguj(&$model)
{
    return 'zaloguj_view';
}

function zarejestruj(&$model)
{
    return 'zarejestruj_view';
}

function zarejestruj_check(&$model)
{
        $login = (string)$_POST['login'];
        $password = (string)$_POST['password'];
        $password2 = (string)$_POST['password2'];
        $email = (string)$_POST['email'];
        
        $errors = [];
        
        if(!check_login_in_database($login)) {
            $errors[] = "Niestety podany login jest zajety. Prosze wybrac inny.";
        }
        
        if (!preg_match("/[a-zA-Z0-9_]{3,30}/i", $login)) {
            $errors[] = "Login musi zawierac znaki a-z, A-z, 0-9 oraz miec dlugosc od 3 do 30.";
        }
        
        if ($password !== $password2) {
            $errors[] = "Hasla nie sa takie same.";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "E-mail jest nieprawidlowy.";
        }
        
        if (empty($errors)) {
            add_user($login, $password, $email);            
            $messages = "Zarejestrowales sie poprawnie.";			
        } else {
            $messages = $errors;
        }
		
		$model['messages'] = $messages;
		
    return 'zarejestruj_check_view';
}

function zaloguj_check(&$model)
{
        $login = (string)$_POST['login'];
        $password = (string)$_POST['password'];
        
        $user = get_user_by_login($login);
        
        if (password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['user'] = $user;
			if(isset($_COOKIE['saved_photo_'.($_SESSION['user']['_id'])])){
				$unserialized_cookie = unserialize($_COOKIE['saved_photo_'.($_SESSION['user']['_id'])]);
				$_SESSION['memorized_photos'] = $unserialized_cookie;
			}
            $messages = "Witaj {$user['login']}. Zostales poprawnie zalogowany.";
        }
        else {
            $messages = "Niepoprawny login lub haslo.";
        }
		
		$model['messages'] = $messages;
    	
    return 'zaloguj_check_view';
}

function wyloguj(&$model) 
{
	session_destroy();
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	);
	
	$view_name = REDIRECT_PREFIX."/zaloguj";
	return $view_name;
}

function profil(&$model) 
{
	return 'profil_view';
}

function zapamietaj (&$model)
{	
	if (isset($_POST['memorized'])) {
		$cookie_value = $_POST['memorized'];
		setcookie('saved_photo_'.($_SESSION['user']['_id']),serialize($cookie_value),time() + 36000);
		$_SESSION['memorized_photos'] = $_POST['memorized'];
	}
	else
	{
		$cookie_value = null;
		setcookie('saved_photo_'.($_SESSION['user']['_id']),serialize($cookie_value),time() + 36000);
		$_SESSION['memorized_photos'] = null;		
	}
	
		
	$view_name = REDIRECT_PREFIX."/galeria";
	return $view_name;	
}

function zapomnij (&$model)
{	
	if (isset($_POST['memorized'])) {
		$cookie_value = [];
		foreach($_SESSION['memorized_photos'] as $to_memorize) {
			$still_remember = true;
			foreach($_POST['memorized'] as $to_forget) {
				if($to_memorize == $to_forget){
					$still_remember = false;
				}
			}
			if ($still_remember) {
				$cookie_value[] = $to_memorize;
			}
		}
		$_SESSION['memorized_photos'] = $cookie_value;
		setcookie('saved_photo_'.($_SESSION['user']['_id']),serialize($cookie_value),time() + 36000);
	}
		
	$view_name = REDIRECT_PREFIX."/galeria_zapamietane";
	return $view_name;	
}

function search (&$model)
{
	
	$photos = get_photos_from_database();
	if ( strlen($_POST['zapytanie']) > 0 ){
		foreach($photos as $photo){
			if(strcasecmp(substr($photo['title'], 0, strlen($_POST['zapytanie'])), $_POST['zapytanie']) === 0){
				$model['photos'][] = $photo;
			}
		}
	}
	else{
		$photos = [];
	}
	
	$view_name = PARTIAL_PREFIX."/search_view";
	return $view_name;
}

function galeria_search (&$model)
{
	return "galeria_search_view";
}

function mojtrening (&$model)
{
	return "mojtrening_view";
}

function mojtreningklata (&$model)
{
	return "mojtreningklata_view";
}

function mojtreningbarki (&$model)
{
	return "mojtreningbarki_view";
}

function mojtreningnogi (&$model)
{
	return "mojtreningnogi_view";
}

function mojtreningplecy (&$model)
{
	return "mojtreningplecy_view";
}

function index (&$model)
{
	return "index_view";
}