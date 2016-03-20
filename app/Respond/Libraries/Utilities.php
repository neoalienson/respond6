<?php

namespace App\Respond\Libraries;

use \Firebase\JWT\JWT;

class Utilities
{
    /**
     * Build pages.json
     *
     * @param {string} $path the recipient's email address
     * @return {Array} list of HTML fiels
     */
    public static function ListPages($dir, $user, $site) {

      // list files
      $files = Utilities::ListFiles($dir, $site->Id);

      // setup array to return
      $arr = array();

      // setup timestamp as JS date
      $timestamp = gmdate('D M d Y H:i:s O', time());

      foreach($files as $file){

        // defaults
        $title = '';
        $description = '';
        $keywords = '';
        $callout = '';
        $layout = 'content';
        $url = $file;

        if($url == 'index.html') {
          $layout = 'home';
        }

        // set full file path
        $file = app()->basePath().'/public/sites/'.$site->Id.'/'.$file;

        // open with phpQuery
        \phpQuery::newDocumentFileHTML($file);

        $title = pq('title')->html();
        $description = pq('meta[name=description]')->attr('content');
        $keywords = pq('meta[name=keywords]')->attr('content');

        // cleanup url
        $url = ltrim($url, '/');

        // strip any trailing .html from url
        $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

        $data = array(
          'Title' => $title,
          'Description' => $description,
          'Keywords' => $keywords,
          'Callout' => $callout,
          'Url' => $url,
          'BeginDate' => '',
          'EndDate' => '',
          'Location' => '',
          'LatLong' => '',
          'Layout' => 'content',
          'Language' => 'en',
          'FirstName' => $user->FirstName,
          'LastName' => $user->LastName,
          'LastModifiedBy' => $user->Email,
          'LastModifiedDate' => $timestamp
        );


        // push array
        array_push($arr, $data);

      }

      return $arr;

    }

    /**
     * Returns all HTML files for a given path
     *
     * @param {string} $path the recipient's email address
     * @return {Array} list of HTML fiels
     */
    public static function ListFiles($dir, $siteId) {

	    $root = scandir($dir);

	    if(!isset($result)) {
  	    $result = array();
	    }

      foreach($root as $value) {

          if($value === '.' || $value === '..' || $value === '.htaccess') {
            continue;
          }

          if(is_file("$dir/$value")) {

            $file = "$dir/$value";

            $ext = pathinfo($file, PATHINFO_EXTENSION);

            //echo $ext;
            if($ext == 'html') {
              $paths = explode('sites/'.$siteId.'/', "$dir/$value");

              $restrict = array('components/', 'css/', 'data/', 'files/', 'js/', 'locales/', 'fragments/', 'themes/');

              $is_restricted = FALSE;

              foreach($restrict as $item) {

                // TODO: MAKE SURE THE FILE DOES NOT START WITH A RESTRICTED PATH
                if(substr($paths[1], 0, strlen($item)) === $item) {
                  $is_restricted = TRUE;
                }

              }

              if($is_restricted === FALSE){
                $result[]=$paths[1];
              }


            }
            else {
              continue;
            }

            continue;
          }

          foreach(Utilities::ListFiles("$dir/$value", $siteId) as $value)
          {
              $result[]=$value;
          }

      }

      return $result;

    }


    /**
     * Returns all routes
     *
     * @param {string} $path the recipient's email address
     * @return {Array} list of HTML fiels
     */
    public static function ListRoutes($dir, $siteId) {

      $result = array();

      $restrict = array('components', 'css', 'data', 'files', 'js', 'locales', 'fragments', 'themes');

      $cdir = scandir($dir);

      foreach ($cdir as $key => $value) {

        if (!in_array($value, array(".",".."))) {

           if (is_dir("$dir/$value")) {

              $paths = explode('sites/'.$siteId.'/', "$dir/$value");

              $is_restricted = FALSE;

              foreach($restrict as $item) {

                // TODO: MAKE SURE THE FILE DOES NOT START WITH A RESTRICTED PATH
                if(substr($paths[1], 0, strlen($item)) === $item) {
                  $is_restricted = TRUE;
                }

              }

              if($is_restricted === FALSE){

                $arr = Utilities::ListRoutes("$dir/$value", $siteId);

                $result[] = '/'.$paths[1];
                $result = array_merge($result, $arr);

              }
           }

        }

      }

      return $result;

    }


    /**
     * Sends an email from a specified file
     *
     * @param {string} $to the recipient's email address
     * @param {string} $from the sender's email address
     * @param {string} $fromName the name of the sender
     * @param {string} $subject the subject of the email
     * @param {Array} $replace an associative array of strings to replace
     * @param {string} $file the file to send
     * @return void
     */
    public static function SendEmailFromFile($to, $from, $fromName, $subject, $replace, $file){

	    if(file_exists($file)){

        $content = file_get_contents($file);

        // walk through and replace values in associative array
        foreach ($replace as $key => &$value) {

  			    $content = str_replace($key, $value, $content);
  			    $subject = str_replace($key, $value, $subject);

  			}

  			// send email
  			Utilities::SendEmail($to, $from, $fromName, $subject, $content);

  			return true;

      }
      else {
        echo 'File does not exist='.$file;

        return false;
      }

    }

    /**
     * Sends an email
     *
     * @param {string} $to the recipient's email address
     * @param {string} $from the sender's email address
     * @param {string} $fromName the name of the sender
     * @param {string} $subject the subject of the email
     * @param {string} $content the content of the email
     * @return void
     */
    public static function SendEmail($to, $from, $fromName, $subject, $content){

      $mail = new \PHPMailer;

      // setup SMTP
      if(env('IS_SMTP') == true){

        $mail->isSMTP();                    // Set mailer to use SMTP
        $mail->Host = env('SMTP_HOST');  			// Specify main and backup server

        if (env('SMTP_PORT')){
          $mail->Port = env('SMTP_PORT');
        }

        $mail->SMTPAuth = env('SMTP_AUTH');        // Enable SMTP authentication
        $mail->Username = env('SMTP_USERNAME');    // SMTP username
        $mail->Password = env('SMTP_PASSWORD');    // SMTP password
        $mail->SMTPSecure = env('SMTP_SECURE');    // Enable encryption, 'ssl' also accepted
        $mail->CharSet = 'UTF-8';

      }

      $mail->From = $from;
      $mail->FromName = $fromName;
      $mail->addAddress($to, '');
      $mail->isHTML(true);

      $mail->Subject = $subject;
      $mail->Body = html_entity_decode($content, ENT_COMPAT, 'UTF-8');

      if(!$mail->send()) {
        return true;
      }

      return false;

    }

    /**
     * Creates a JWT token,
     * #ref: https://github.com/firebase/php-jwt, https://auth0.com/blog/2014/01/07/angularjs-authentication-with-cookies-vs-token/
     *
     * @param {string} $userId the id of the user
     * @param {string} $siteId the id of the site
     * @return void
     */
    public static function CreateJWTToken($email, $siteId){

	    // create token
  		$token = array(
  		    'Email' => $email,
  		    'SiteId' => $siteId,
  		    'Expires' => (strtotime('NOW') + (3*60*60)) // expires in an hour
  		);

      // create JWT token, #ref: https://github.com/firebase/php-jwt
		  $jwt_token = JWT::encode($token, env('JWT_KEY'));

      // return token
      return $jwt_token;
    }

    /**
     * Validates a JWT token,
     * #ref: https://github.com/firebase/php-jwt, https://auth0.com/blog/2014/01/07/angularjs-authentication-with-cookies-vs-token/
     *
     * @param {string} $userId the id of the user
     * @param {string} $siteId the id of the site
     * @return void
     */
    public static function ValidateJWTToken($auth){

  		// locate token
  		if(strpos($auth, 'Bearer') !== false){

  			$jwt = str_replace('Bearer ', '', $auth);

  			try{

  				// decode token
  				$jwt_decoded = JWT::decode($jwt, env('JWT_KEY'), array('HS256'));

  				if($jwt_decoded != NULL){

  					// check to make sure the token has not expired
  					if(strtotime('NOW') < $jwt_decoded->Expires){
  						return $jwt_decoded;
  					}
  					else{
  						return NULL;
  					}

  				}
  				else{
  					return NULL;
  				}

  				// return token
  				return $jwt_decoded;

  			}
  			catch(Exception $e) {
  				return NULL;
  			}

  		}
  		else{
  			return NULL;
  		}

    }

    /**
     * Saves content to a file (creates the directory if needed)
     *
     * @param {string} $dir the directory
     * @param {string} $filename the filename of the new file
     * @param {string} $content the content of the new file
     * @return void
     */
  	public static function SaveContent($dir, $filename, $content){
  		$full = $dir.$filename;

  		if(!file_exists($dir)){
  			mkdir($dir, 0777, true);
  		}

  		$fp = @fopen($full, 'w'); // Generate a new cache file
  		@fwrite($fp, $content); // save the contents of output buffer to the file
  		@fclose($fp);
  	}

  	/**
     * Copies a directory
     *
     * @param {string} $src the source
     * @param {string} $dst the destination
     * @return void
     */
    public static function CopyDirectory($src, $dst){
      $dir = opendir($src);

      if(!file_exists($dst)){
  			mkdir($dst, 0777, true);
  		}

      while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                Utilities::CopyDirectory($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
      }
      closedir($dir);
    }

}