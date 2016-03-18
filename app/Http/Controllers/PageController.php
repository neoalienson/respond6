<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\Page;

class PageController extends Controller
{

  /**
   * Lists the pages for given site
   *
   * @return Response
   */
  public function listAll(Request $request)
  {

    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    // list pages in the site
    $arr = Page::ListAll($email, $siteId);

    return response()->json($arr);

  }

  /**
   * Lists the routes for given site
   *
   * @return Response
   */
  public function listRoutes(Request $request)
  {

    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    // get base path for the site
    $dir = $file = app()->basePath().'/public/sites/'.$siteId;

    $arr = array_merge(array('/'), Utilities::ListRoutes($dir, $siteId));

    return response()->json($arr);

  }

  /**
   * Saves the page and the fragment
   *
   * @return Response
   */
  public function save(Request $request)
  {
    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');
    
    // get url & changes
    $url = $request->json()->get('url');
    $changes = $request->json()->get('changes');
    
    // get site and user
    $site = Site::GetById($siteId);
    $user = User::GetByEmail($siteId, $email);
    
    // remove site and .html from url
    $url = str_replace($siteId.'/', '', $url);
    $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);
    
    // content placeholder
    $content = '';
    
    // get content
    foreach($changes as $change) {

      if($change['selector'] == '[role="main"]') {
        $content = $change['html'];
      }
    
    }
    
    // edit the page
    $success = Page::Edit($url, $content, $site, $user);
    
    // show response
    if($success == TRUE) {
      return response('OK', 200);
    }
    else {
      return response('Page not found', 400);
    }    

  }
  
  /**
   * Saves the page settings
   *
   * @return Response
   */
  public function settings(Request $request)
  {
    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');
    
    // get url & changes
    $url = $request->json()->get('url');
    $title = $request->json()->get('title');
    $description = $request->json()->get('description');
    $keywords = $request->json()->get('keywords');
    $callout = $request->json()->get('callout');
    $layout = $request->json()->get('layout');
    $language = $request->json()->get('language');
    $timestamp = gmdate('D M d Y H:i:s O', time());
    
    $data = array(
      'Title' => $title,
      'Description' => $description,
      'Keywords' => $keywords,
      'Callout' => $callout,
      'Url' => $url,
      'Layout' => 'content',
      'Language' => 'en',
      'LastModifiedBy' => $email,
      'LastModifiedDate' => $timestamp
    );
    
    // get site and user
    $site = Site::GetById($siteId);
    $user = User::GetByEmail($siteId, $email);
    
    // edit the page
    $success = Page::EditSettings($data, $site, $user);
    
    // show response
    if($success == TRUE) {
      return response('OK', 200);
    }
    else {
      return response('Page not found', 400);
    }    

  }

  /**
   * Adds the page and the fragment
   *
   * @return Response
   */
  public function add(Request $request)
  {
    // get request data
    $email = $request->input('email');
    $siteId = $request->input('siteId');

    // get url, title and description
    $url = $request->json()->get('url');
    $title = $request->json()->get('title');
    $description = $request->json()->get('description');
    $timestamp = gmdate('D M d Y H:i:s O', time());

    // get the site
    $site = Site::GetById($siteId);
    $user = User::GetByEmail($siteId, $email);

    // strip any leading slashes from url
    $url = ltrim($url, '/');

    // strip any trailing .html from url
    $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);
    
    // set page data
    $data = array(
      'Title' => $title,
      'Description' => $description,
      'Keywords' => '',
      'Callout' => '',
      'Url' => $url,
      'Layout' => 'content',
      'Language' => 'en',
      'LastModifiedBy' => $email,
      'LastModifiedDate' => $timestamp
    );
    
    // add a page
    $page = Page::Add($data, $site, $user);
    
    // return OK
    return response('OK, page added at = '.$page->Url, 200);

  }

}