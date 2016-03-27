<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\File;

class FileController extends Controller
{

  /**
   * Lists all files for a site
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


  /**
   * Uploads a file
   *
   * @return Response
   */
  public function upload(Request $request)
  {
    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');
    
    // get site
    $site = Site::GetById($siteId);
    
    // get file
    $file = $request->file('file');
    
    // get file info
    $filename = $file->getClientOriginalName(); 
		$contentType = $file->getMimeType();
		$size = intval($file->getClientSize()/1024);
		
		// get the extension
		$ext = $file->getClientOriginalExtension();
		
    // allowed filetypes
    $allowed = explode(',', env('ALLOWED_FILETYPES'));
    
    // trim and lowercase all items in the aray
    $allowed = array_map('trim', $allowed);
		$allowed = array_map('strtolower', $allowed);
		
		// directory to save
    $directory = app()->basePath().'/public/sites/'.$site->Id.'/files';
		
		// save image
    if($ext=='png' || $ext=='jpg' || $ext=='gif' || $ext == 'svg'){ // upload image
    
      // move the file
      $file->move($directory, $filename);
      
      // set path
      $path = $directory.'/'.$filename;
    
      $arr = Utilities::CreateThumb($site, $path, $filename);
    
      // set local URL
      $url = 	$site->Domain;
    
      // create array
      $arr = array(
        'filename' => $filename,
        'fullUrl' => $url.'/files/'.$filename,
        'thumbUrl' => $site->Domain.'/files/thumbs/'.$filename,
        'extension' => $ext,
        'isImage' => true,
        'width' => $arr['width'],
        'height' => $arr['height'],
      );
    
    }
    else if(in_array($ext, $allowed)){ // save file if it is allowed
    
      // move the file
      $file->move($directory, $filename);
      
      // set url
      $url = 	$site->Domain;
      
      $arr = array(
        'filename' => $filename,
        'fullUrl' => $url.'/files/'.$filename,
        'thumbUrl' => NULL,
        'extension' => $ext,
        'isImage' => false,
        'width' => -1,
        'height' => -1
        );
    }
    else{
    
      return response('Unauthorized', 401);
    
    }
		
    // return OK
    return response()->json($arr);

  }

}