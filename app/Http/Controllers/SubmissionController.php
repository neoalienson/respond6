<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\Submission;

class SubmissionController extends Controller
{

  /**
   * Lists all branding for a site
   *
   * @return Response
   */
  public function listAll(Request $request)
  {

    // get request data
    $email = $request->input('auth-email');
    $siteId = $request->input('auth-id');

    // list settings for the site
    $arr = Submission::listAll($siteId);

    return response()->json($arr);

  }

  /**
   * Adds a submission
   *
   * @return Response
   */
  public function add(Request $request)
  {
    // get the site
    $siteId = $request->json()->get('siteId');
    $url = $request->json()->get('url');
    $formId = $request->json()->get('formId');
    $fields = $request->json()->get('fields');
    $timestamp = gmdate('D M d Y H:i:s O', time());
    
    $name = 'New Submission';
    
    if(sizeof($fields) > 0) {
      $name = $fields[0]['value'];
    }
    
    $arr = array(
      'id' => Utilities::getGUID(),
      'name' => $name,
      'url' => $url,
      'formId' => $formId,
      'date' => $timestamp,
      'fields' => $fields
    );
    
    // create a submission from the json file
    $submission = new Submission($arr);
    
    // save the submission
    $submission->save($siteId);

    return response('Ok', 200);

  }
  
  /**
   * Removes a submission
   *
   * @return Response
   */
  public function remove(Request $request)
  {
    // get request data
    $siteId = $request->input('auth-id');

    // get id of submission
    $id = $request->json()->get('id');

    $submission = Submission::getById($id, $siteId);

    $submission->remove($siteId);

    // return OK
    return response('OK, submission removed at = '.$submission->id, 200);

  }

}