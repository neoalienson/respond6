<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\File;

class ImageController extends Controller
{

  /**
   * Lists all images for a site
   *
   * @return Response
   */
  public function listAll(Request $request)
  {

    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    $arr = array();

    return response()->json($arr);

  }

}