<?php

namespace App\Http\Controllers;

use App\Respond\Models\Site;
use \Illuminate\Http\Request;

class SiteController extends Controller
{

  /**
  * Retrieve the user for the given ID.
  *
  * @return Response
  */
  public function test()
  { 
  
    return '[Respond] API works!';
  
  }
  
  /**
  * Test the auth
  *
  * @return Response
  */
  public function testAuth(Request $request)
  {
  
    $siteId = $request->input('siteId');
    $userId = $request->input('userId');
    
    return '[Respond] Authorized, siteId='.$siteId.' and userId='.$userId;
  
  }
  
  /**
  * Validates the site id
  *
  * @return Response
  */
  public function validateId(Request $request)
  {
  
    $id = $request->input('id');
    
    $is_unique = Site::isIdUnique($id);
    
    if($is_unique==false) {
    
      // send conflict(409)
      return response('ID is not unique', 409);
      
    }
    else {
    
      // return 200
      return response('Ok', 200);
    
    }
  
  }

  /**
   * Creates the site
   *
   * @return Response
   */
  public function create(Request $request)
  {

    // get request
    $name = $request->json()->get('name');
    $theme = $request->json()->get('theme');
    $email = $request->json()->get('email');
    $password = $request->json()->get('password');
    $passcode = $request->json()->get('passcode');
    
    if($passcode == env('PASSCODE')) {
      
      $arr = Site::create($name, $theme, $email, $password);
      
      return response()->json($arr);
    }
    else {
      return response('Passcode invalid', 401);
    }

  }

}