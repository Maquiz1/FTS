<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php 
class Redirect{
	public static function to($location = null){
      if($location){
          if(is_numeric($location)){
          	switch($location){
              case 404:
              header('HTTP/1.0 404 Not found');
              include 'includes/error/404.php';
              break;
          	}

          }
          header('Location: '.$location);
          exit();
      }
	} 
}