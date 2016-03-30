<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Respond\Libraries\Utilities;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\File;

class FileController extends Controller
{

  /**
   * Lists all images for a site
   *
   * @return Response
   */
  public function listImages(Request $request)
  {

    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    $arr = File::ListImages($siteId);

    return response()->json($arr);

  }

  /**
   * Lists all files for a site
   *
   * @return Response
   */
  public function listFiles(Request $request)
  {

    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    // get a reference to the site
    $site = Site::GetById($siteId);

    // list files
    $arr = File::ListFiles($siteId);

    // set image extensions
    $image_exts = array('gif', 'png', 'jpg', 'svg');

    $files = array();

    foreach($arr as $file) {

      $filename = str_replace('files/', '', $file);

      $path = app()->basePath().'/public/sites/'.$siteId.'/files/'.$filename;

      // get extension
      $parts = explode(".", $filename);
      $ext = end($parts); // get extension
      $ext = strtolower($ext); // convert to lowercase

      // determine if it is an image
      $is_image = in_array($ext, $image_exts);

      // get the filesize
      $size = filesize($path);

      if($is_image === TRUE) {
        $width = 0;
        $height = 0;

        try{
          list($width, $height, $type, $attr) = Utilities::getImageInfo($path);
        }
        catch(Exception $e){}

        // set url, thumb
        $url = $thumb = $site->Domain.'/files/'.$filename;

        // check for thumb
        if(file_exists(app()->basePath().'/public/sites/'.$siteId.'/files/thumbs/'.$filename)) {
          $thumb = $site->Domain.'/files/thumbs/'.$filename;
        }

        // push file to the array
        array_push($files, array(
          'Name' => $filename,
          'Url' => $url,
          'Thumb' => $thumb,
          'Extension' => $ext,
          'IsImage' => $is_image,
          'Width' => $width,
          'Height' => $height,
          'Zize' => number_format($size / 1048576, 2)
        ));

      }
      else {

        // push file to the array
        array_push($files, array(
          'Name' => $filename,
          'Url' => $site->Domain.'/files/'.$filename,
          'Thumb' => '',
          'Extension' => $ext,
          'IsImage' => $is_image,
          'Width' => NULL,
          'Height' => NULL,
          'Size' => number_format($size / 1048576, 2)
        ));

      }

    }


    return response()->json($files);

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