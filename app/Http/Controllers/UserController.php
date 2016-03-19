<?php

namespace App\Http\Controllers;

use App\Respond\Models\User;
use App\Respond\Models\Site;
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
    $siteId = $request->json()->get('id');

    // get site by its friendly id
    $site = Site::GetById($siteId);

    if ($site != NULL) {

      // get the user from the credentials
      $user = User::GetByEmailPassword($site->Id, $email, $password);

      if($user != NULL) {

        // get the photoURL
        $fullPhotoUrl = '';

      	// set photo url
      	if($user->Photo != '' && $user->Photo != NULL){

      		// set images URL
          $imagesURL = $site->Domain;

        	$fullPhotoUrl = $imagesURL.'/files/thumbs/'.$user->Photo;

      	}

        // return a subset of the user array
        $returned_user = array(
        	'Email' => $user->Email,
        	'FirstName' => $user->FirstName,
        	'LastName' => $user->LastName,
        	'Photo' => $user->Photo,
        	'FullPhotoUrl' => $fullPhotoUrl,
        	'Language' => $user->Language,
        	'Role' => $user->Role,
        	'SiteId' => $site->Id
        );

        // send token
        $params = array(
        	'user' => $returned_user,
        	'token' => Utilities::CreateJWTToken($user->Email, $site->Id)
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

    // get site
    $site = Site::GetById($id);

    if($site != NULL) {

      // get user
      $user = User::GetByEmail($site->Id, $email);

      if($user != NULL) {

        $user->Token = uniqid();

        // save
        $site->SaveUser($user);

        // send email
        $to = $user->Email;
        $from = env('EMAILS_FROM');
        $fromName = env('EMAILS_FROM_NAME');
        $subject = env('BRAND').': Reset Password';
        $file = app()->basePath().'/resources/emails/reset-password.html';

        // create strings to replace
        $resetUrl = env('APP_URL').'/reset/'.$site->Id.'/'.$user->Token;

        $replace = array(
          '{{brand}}' => env('BRAND'),
          '{{reply-to}}' => env('EMAILS_FROM'),
          '{{reset-url}}' => $resetUrl
        );

        // send email from file
        Utilities::SendEmailFromFile($to, $from, $fromName, $subject, $replace, $file);

        return response('OK', 200);

      }

    }

    return response('Unauthorized', 401);

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
    $id = $request->json()->get('id');

    $site = Site::GetById($id);

    if($site != NULL) {

      // get the user from the credentials
      $user = User::GetByToken($site->Id, $token);

      if($user!=null){

        // update the password
        $user->Password = password_hash($password, PASSWORD_DEFAULT);
        $user->Token = '';

        $site->SaveUser($user);

        // return a successful response (200)
        return response('OK', 200);

      }
      else{

        // return a bad request
        return response('Token invalid', 400);

      }

    }
    else {
      // return a bad request
      return response('Token invalid', 400);
    }

  }

}