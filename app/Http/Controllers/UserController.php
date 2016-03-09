<?php

namespace App\Http\Controllers;

use App\Respond\Database\User;
use App\Respond\Database\Site;
use App\Respond\Libraries\Utilities;
use \Illuminate\Http\Request;

class UserController extends Controller
{

  /**
   * Test the auth
   *
   * @return Response
   */
  public function auth(Request $request)
  {
    return response('OK', 200);
  }

  /**
   * Logs the user into the application
   *
   * @return Response
   */
  public function login(Request $request)
  {

    $email = $request->json()->get('email');
    $password = $request->json()->get('password');
    $id = $request->json()->get('id');

    // get site by its friendly id
    $site = Site::GetByFriendlyId($id);

    if ($site != NULL) {

      // get the user from the credentials
      $user = User::GetByEmailPassword($email, $site->SiteId, $password);

      if($user != NULL) {

        // get the photoURL
        $fullPhotoUrl = '';

      	// set photo url
      	if($user->PhotoUrl != '' && $user->PhotoUrl != NULL){

      		// set images URL
          $imagesURL = $site->Domain;

        	$fullPhotoUrl = $imagesURL.'/files/thumbs/'.$user->PhotoUrl;

      	}

        // return a subset of the user array
        $returned_user = array(
        	'Email' => $user->Email,
        	'FirstName' => $user->FirstName,
        	'LastName' => $user->LastName,
        	'PhotoUrl' => $user->PhotoUrl,
        	'FullPhotoUrl' => $fullPhotoUrl,
        	'Language' => $user->Language,
        	'Role' => $user->Role,
        	'SiteAdmin' => $user->SiteAdmin,
        	'SiteId' => $user->SiteId,
        	'UserId' => $user->UserId
        );


        // send token
        $params = array(
        	'user' => $returned_user,
        	'token' => Utilities::CreateJWTToken($user->UserId, $user->SiteId, $site->FriendlyId)
        );

        // return a json response
        return response()->json($params);

      }
      else {
        return response('Unauthorized', 401);
      }


    }
    else {
      return response('Unauthorized', 401);
    }

  }

  /**
   * Creates a token to reset the password for the user
   *
   * @return Response
   */
  public function forgot(Request $request)
  {

    $email = $request->json()->get('email');
    $id = $request->json()->get('id');

    // get site by its friendly id
    $site = Site::GetByFriendlyId($id);

    if ($site != NULL) {

      // get the user from the credentials
      $user = User::GetByEmail($email, $site->SiteId);

      if($user != NULL) {

        // set token
        $token = urlencode(User::SetToken($user->UserId));

        // send email
        $to = $email;
        $from = env('EMAILS_FROM');
        $fromName = env('EMAILS_FROM_NAME');
        $subject = env('BRAND').': Reset Password';
        $file = app()->basePath().'/app/Respond/Resources/Emails/reset-password.html';

        // create strings to replace
        $resetUrl = env('APP_URL').'/reset/'.$site->FriendlyId.'/'.$token;

        $replace = array(
          '{{brand}}' => env('BRAND'),
          '{{reply-to}}' => env('EMAILS_FROM'),
          '{{reset-url}}' => $resetUrl
        );

        // send email from file
        Utilities::SendEmailFromFile($to, $from, $fromName, $subject, $replace, $file);

        return response('OK', 200);

      }
      else {
        return response('Unauthorized', 401);
      }

    }
    else {
      return response('Unauthorized', 401);
    }

  }

  /**
   * Resets the password
   *
   * @return Response
   */
  public function reset(Request $request)
  {

    $token = $request->json()->get('token');
    $password = $request->json()->get('password');
    $friendlyId = $request->json()->get('id');

    // get site
    $site = Site::GetByFriendlyId($friendlyId);

    // get the user from the credentials
    $user = User::GetByToken($token, $site->SiteId);

    if($user!=null){

      User::EditPassword($user->UserId, $password);

      // return a successful response (200)
      return response('OK', 200);
      
    }
    else{
    
      // return a bad request
      return response('Token invalid', 400);
      
    }

  }

}