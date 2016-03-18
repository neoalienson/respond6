<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

/**
 * Models a page
 */
class Page {

  public $Title;
  public $Description;
  public $Keywords;
  public $Callout;
  public $Url;
  public $Layout;
  public $Stylesheet;
  public $Language;
  public $LastModifiedBy;
  public $LastModifiedDate;
  
  /**
   * Constructs a page from an array of data
   *
   * @param {arr} $data
   */
  function __construct(array $data) {
    foreach($data as $key => $val) {
      if(property_exists(__CLASS__,$key)) {
        $this->$key = $val;
      }
    }
  }


  /**
   * Adds a page
   *
   * @param {arr} $arr array containg page information
   * @param {site} $site object
   * @param {user} $user object
   * @return Response
   */
  public static function Add($data, $site, $user, $content = NULL){

    // create a new page
    $page = new Page($data);

    // create a new snippet for the page
    $dest = app()->basePath().'/public/sites/'.$site->Id;
    $name = $new_name = str_replace('/', '.', $page->Url);
    $fragment = $dest . '/fragments/page/' . $name . '.html';
    
    // avoid dupes
    $x = 1;
    
    while(file_exists($fragment) === TRUE) {

      // increment id and folder
      $new_name = $name.$x;
      $fragment = $dest . '/fragments/page/' . $new_name . '.html';
      $x++;
    
    }
    
    // update url
    $page->Url = str_replace('.', '/', $new_name);
    $data['Url'] = $page->Url;
    
    // default html for a new page
    if($content == NULL) {
      $content = '<div class="block row"><div class="col col-md-12"><h1>'.$page->Title.'</h1><p>'.$page->Description.'</p></div></div>';
    }
    
    // make directory
    $dir = $dest . '/fragments/page/';
    
    if(!file_exists($dir)){
			mkdir($dir, 0777, true);
		}

    // place contents in template
    file_put_contents($fragment, $content);

    // publish the page
    $location = Publish::PublishPage($site, $page, $user);

    // get base path for the site
    $json = $file = app()->basePath().'/public/sites/'.$site->Id.'/data/pages.json';

    // open json
    if(file_exists($json)) {

      $json = file_get_contents($json);

      // decode json file
      $pages = json_decode($json, true);

      // push page to array
      array_push($pages, $data);

      // save array
      file_put_contents($file, json_encode($pages));

    }

    // return the page
    return $page;

  }
  
  /**
   * Edits a page
   *
   * @param {arr} $arr array containg page information
   * @param {site} $site object
   * @param {user} $user object
   * @return Response
   */
  public static function Edit($url, $content, $site, $user){

    $page = Page::GetByUrl($url, $site->Id);
    
    if($page != NULL) {
    
      // get template file from URL
      $dest = app()->basePath().'/public/sites/'.$site->Id;
      $name = str_replace('/', '.', $page->Url);
      $fragment = $dest . '/fragments/page/' . $name . '.html';
      
      // update template
      file_put_contents($fragment, $content);
      
      // publish the page
      Publish::PublishPage($site, $page, $user);

      return TRUE;
      
    }
    else {
      
      return FALSE;
      
    }

  }
  
  /**
   * Edits the settings for a page
   *
   * @param {arr} $arr array containg page information
   * @param {site} $site object
   * @param {user} $user object
   * @return Response
   */
  public static function EditSettings($data, $site, $user){
  
    echo($data['Url']);
  
    $current_page = NULL;
    
    // get pages.json
    $file = app()->basePath().'/public/sites/'.$site->Id.'/data/pages.json';
    
    if(file_exists($file)) {
      
      $json = file_get_contents($file);

      // decode json file
      $pages = json_decode($json, true);
      
      $i = 0;
      
      foreach($pages as $page){
        
        // update page
        if($page['Url'] == $data['Url']) {
        
          echo('match');
          
          $page['Title'] = $data['Title'];
          $page['Description'] = $data['Description'];
          $page['Keywords'] = $data['Keywords'];
          $page['Callout'] = $data['Callout'];
          $page['Layout'] = $data['Layout'];
          $page['Language'] = $data['Language'];
          $page['LastModifiedBy'] = $data['LastModifiedBy'];
          $page['LastModifiedDate'] = $data['LastModifiedDate'];
          
          // update array
          $pages[$i] = $page;
          
          // create a new page
          $current_page = new Page($page);
          
          $i++;
          
        }
        
      }
      
      // save pages
      file_put_contents($file, json_encode($pages));
      
    }
    
    // publish
    if($current_page != NULL) {
    
      // publish the page
      Publish::PublishPage($site, $current_page, $user);

      return TRUE;
      
    }
    else {
      
      return FALSE;
      
    }

  }
  
  
  /**
   * Retrieves page data based on a url
   *
   * @param {string} $url url of page
   * @return Response
   */
  public static function GetByUrl($url, $id){
  
    $file = app()->basePath().'/public/sites/'.$id.'/data/pages.json';
    
    if(file_exists($file)) {
      
      $json = file_get_contents($file);

      // decode json file
      $pages = json_decode($json, true);
      
      foreach($pages as $page){
        
        if($page['Url'] == $url) {
          
          // create a new page
          return new Page($page);
          
        }
        
      }
      
    }
    
    return NULL;

  }

  /**
   * Lists pages
   *
   * @param {string} $userId
   * @param {string} $siteId friendly id of site (e.g. site-name)
   * @return Response
   */
  public static function ListAll($email, $siteId){

    $arr = array();

    // get base path for the site
    $json = $file = app()->basePath().'/public/sites/'.$siteId.'/data/pages.json';

    if(file_exists($json)) {

      $json = file_get_contents($json);

      // decode json file
      $arr = json_decode($json, true);

    }
    else{

      // set dir
      $dir = app()->basePath().'/public/sites/'.$siteId;

      // list pages in the site
      $arr = Utilities::ListPages($dir, $email, $siteId);

      // encode arr
      $content = json_encode($arr);

      // update content
      file_put_contents($file, $content);

    }

    return $arr;

  }


}