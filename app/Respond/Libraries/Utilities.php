<?php

namespace App\Respond\Libraries;

use \Firebase\JWT\JWT;

class Utilities
{

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
    public static function CreateJWTToken($userId, $siteId){

	    // create token
  		$token = array(
  		    'UserId' => $userId,
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
    public static function ValidateJWTToken(){

		  $auth = isset($_SERVER['HTTP_X_AUTH']) ? $_SERVER['HTTP_X_AUTH'] : '';

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

}