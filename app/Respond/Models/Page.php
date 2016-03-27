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
  public $Photo;
  public $Thumb;
  public $Layout;
  public $Language;
  public $Direction;
  public $FirstName;
  public $LastName;
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
      $content = html_entity_decode($site->DefaultContent);
      $content = str_replace('{{page.Title}}', $page->Title, $content);
      $content = str_replace('{{page.Description}}', $page->Description, $content);
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
  public static function Edit($url, $changes, $site, $user){

    // get a reference to the page object
    $page = Page::GetByUrl($url, $site->Id);

    // get page
    $location = app()->basePath().'/public/sites/'.$site->Id.'/'.$url.'.html';

    if($page != NULL && file_exists($location)) {

      // get html
      $html = file_get_contents($location);

      // get phpQuery doc
      $doc = \phpQuery::newDocument($html);

      // content placeholder
      $main_content = '';

      // get content
      foreach($changes as $change) {

        $selector = $change['selector'];

        // set main content
        if($selector == '[role="main"]') {
          $main_content = $change['html'];
        }

        // apply changes to the document
        $doc[$selector]->html($change['html']);

      }

      // remove data-ref attributes
      foreach($doc['[data-ref]'] as $el) {
        pq($el)->removeAttr('data-ref');
      }

      // update the page
      file_put_contents($location, $doc->htmlOuter());

      // save the fragemnt
      $dest = app()->basePath().'/public/sites/'.$site->Id;
      $name = str_replace('/', '.', $page->Url);
      $fragment = $dest . '/fragments/page/' . $name . '.html';

      // update template
      file_put_contents($fragment, $main_content);

      // saves the page
      $page->Save($site, $user);

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

    $page = Page::GetByUrl($data['Url'], $site->Id);

    $page->Title = $data['Title'];
    $page->Description = $data['Description'];
    $page->Keywords = $data['Keywords'];
    $page->Callout = $data['Callout'];
    $page->Language = $data['Language'];
    $page->Direction = $data['Direction'];

    $page->Save($site, $user);

    return TRUE;

  }

  /**
   * Removes a page
   *
   * @param {arr} $arr array containg page information
   * @param {site} $site object
   * @param {user} $user object
   * @return Response
   */
  public function Remove($siteId){

    // remove the page and fragment
    $page = app()->basePath().'/public/sites/'.$siteId.'/'.$this->Url.'.html';
    $name = $new_name = str_replace('/', '.', $this->Url);
    $fragment = app()->basePath().'/public/sites/'.$siteId.'/fragments/page/'.$name.'.html';

    unlink($page);
    unlink($fragment);

    // remove the page from JSON
    $json_file = app()->basePath().'/public/sites/'.$siteId.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $pages = json_decode($json, true);
      $i = 0;

      foreach($pages as &$page){

        // remove page
        if($page['Url'] == $this->Url) {
          unset($pages[$i]);
        }

        $i++;

      }

      // save pages
      file_put_contents($json_file, json_encode($pages));

    }

    return TRUE;

  }

  /**
   * Saves a page
   *
   * @param {string} $url url of page
   * @return Response
   */
  public function Save($site, $user) {

    // set full file path
    $file = app()->basePath() . '/public/sites/' . $site->Id . '/' . $this->Url . '.html';

    // open with phpQuery
    $doc = \phpQuery::newDocument(file_get_contents($file));

    // update the html
    $doc['title']->html($this->Title);
    $doc['meta[name=description]']->attr('content', $this->Description);
    $doc['meta[name=keywords]']->attr('content', $this->Keywords);
    $doc['html']->attr('lang', $this->Language);
    $doc['html']->attr('dir', $this->Direction);
    $doc['meta[name=keywords]']->attr('content', $this->Keywords);

    // get photo and thumb
    $photo = $doc['[role="main"] img:first']->attr('src');
    $thumb = '';

    if ($photo === NULL || $photo === '') {
      $photo = '';
    }
    else {
      if (substr($photo, 0, 4) === "http") {
        $thumb = $photo;
      }
      else {
        $thumb = str_replace('files/', 'files/thumbs/', $photo);
      }

    }

    // set photo and thumb
    $this->Photo = $photo;
    $this->Thumb = $thumb;

    // save page
    file_put_contents($file, $doc->htmlOuter());

    // set timestamp
    $timestamp = date('Y-m-d\TH:i:s.Z\Z', time());

    // edit the json file
    $json_file = app()->basePath().'/public/sites/'.$site->Id.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $pages = json_decode($json, true);

      foreach($pages as &$page){

        // update page
        if($page['Url'] == $this->Url) {

          $page['Title'] = $this->Title;
          $page['Description'] = $this->Description;
          $page['Keywords'] = $this->Keywords;
          $page['Callout'] = $this->Callout;
          $page['Photo'] = $this->Photo;
          $page['Thumb'] = $this->Thumb;
          $page['Layout'] = $this->Layout;
          $page['Language'] = $this->Language;
          $page['Direction'] = $this->Direction;
          $page['LastModifiedBy'] = $user->Email;
          $page['LastModifiedDate'] = $timestamp;

        }

      }

      // save pages
      file_put_contents($json_file, json_encode($pages));

    }



  }


  /**
   * Retrieves page data based on a url
   *
   * @param {string} $url url of page
   * @return Response
   */
  public static function GetByUrl($url, $siteId){

    $file = app()->basePath().'/public/sites/'.$siteId.'/data/pages.json';

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
  public static function ListAll($user, $site){

    $arr = array();

    // get base path for the site
    $json_file = app()->basePath().'/public/sites/'.$site->Id.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $arr = json_decode($json, true);

    }
    else{

      // set dir
      $dir = app()->basePath().'/public/sites/'.$site->Id;

      // list files
      $files = Utilities::ListFiles($dir, $site->Id,
              array('html'),
              array('components/',
                    'css/',
                    'data/',
                    'files/',
                    'js/',
                    'locales/',
                    'fragments/',
                    'themes/'));

      // setup array to return
      $arr = array();

      // setup timestamp as JS date
      $timestamp = date('Y-m-d\TH:i:s.Z\Z', time());

      foreach ($files as $file) {

          // defaults
          $title       = '';
          $description = '';
          $keywords    = '';
          $callout     = '';
          $layout      = 'content';
          $url         = $file;

          if ($url == 'index.html') {
              $layout = 'home';
          }

          // set full file path
          $file = app()->basePath() . '/public/sites/' . $site->Id . '/' . $file;

          // open with phpQuery
          \phpQuery::newDocumentFileHTML($file);

          $title       = pq('title')->html();
          $description = pq('meta[name=description]')->attr('content');
          $keywords    = pq('meta[name=keywords]')->attr('content');

          // get photo and thumb
          $photo = pq('[role="main"] img:first')->attr('src');
          $thumb = '';

          if ($photo === NULL || $photo === '') {
            $photo = '';
          }
          else {
            if (substr($photo, 0, 4) === "http") {
              $thumb = $photo;
            }
            else {
              $thumb = str_replace('files/', 'files/thumbs/', $photo);
            }

          }

          // get language and direction
          $language = pq('html')->attr('lang');
          $direction = pq('html')->attr('dir');

          if ($language === NULL || $language === '') {
            $language = 'en';
          }

          if ($direction === NULL || $direction === '') {
            $direction = 'ltr';
          }

          // cleanup url
          $url = ltrim($url, '/');

          // strip any trailing .html from url
          $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

          $data = array(
              'Title' => $title,
              'Description' => $description,
              'Keywords' => $keywords,
              'Callout' => $callout,
              'Url' => $url,
              'Photo' => $photo,
              'Thumb' => $thumb,
              'Layout' => 'content',
              'Language' => $language,
              'Direction' => $direction,
              'FirstName' => $user->FirstName,
              'LastName' => $user->LastName,
              'LastModifiedBy' => $user->Email,
              'LastModifiedDate' => $timestamp
          );


          // push array
          array_push($arr, $data);

      }

      // encode arr
      $content = json_encode($arr);

      // update content
      file_put_contents($json_file, $content);

    }

    return $arr;

  }


}