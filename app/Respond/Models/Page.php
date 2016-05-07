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

  public $title;
  public $description;
  public $keywords;
  public $callout;
  public $url;
  public $photo;
  public $thumb;
  public $layout;
  public $language;
  public $direction;
  public $firstName;
  public $lastName;
  public $lastModifiedBy;
  public $lastModifiedDate;

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
  public static function add($data, $site, $user, $content = NULL){

    // create a new page
    $page = new Page($data);

    // create a new snippet for the page
    $dest = app()->basePath().'/public/sites/'.$site->id;
    $name = $new_name = str_replace('/', '.', $page->url);
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
    $page->url = str_replace('.', '/', $new_name);
    $data['url'] = $page->url;

    // default html for a new page
    if($content == NULL) {
      $content = html_entity_decode($site->defaultContent);
      $content = str_replace('{{page.Title}}', $page->title, $content);
      $content = str_replace('{{page.title}}', $page->title, $content);
      $content = str_replace('{{page.Description}}', $page->description, $content);
      $content = str_replace('{{page.description}}', $page->description, $content);
    }

    // make directory
    $dir = $dest . '/fragments/page/';

    if(!file_exists($dir)){
			mkdir($dir, 0777, true);
		}

    // place contents in template
    file_put_contents($fragment, $content);

    // publish the page
    $location = Publish::publishPage($site, $page, $user);

    // get base path for the site
    $json = $file = app()->basePath().'/public/sites/'.$site->id.'/data/pages.json';

    // open json
    if(file_exists($json)) {

      $json = file_get_contents($json);

      // decode json file
      $pages = json_decode($json, true);

      // push page to array
      array_push($pages, $data);

      // save array
      file_put_contents($file, json_encode($pages, JSON_PRETTY_PRINT));

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
  public static function edit($url, $changes, $site, $user){

    // get a reference to the page object
    $page = Page::GetByUrl($url, $site->id);

    // get page
    $location = app()->basePath().'/public/sites/'.$site->id.'/'.$url.'.html';

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
      $dest = app()->basePath().'/public/sites/'.$site->id;
      $name = str_replace('/', '.', $page->url);
      $fragment = $dest . '/fragments/page/' . $name . '.html';

      // make fragments dir
      if(!file_exists($dest . '/fragments/page/')) {
  			mkdir($dest . '/fragments/page/', 0777, true);
  		}

      // update template
      file_put_contents($fragment, $main_content);

      // saves the page
      $page->save($site, $user);

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
  public static function editSettings($data, $site, $user){

    $page = Page::getByUrl($data['url'], $site->id);

    $page->title = $data['title'];
    $page->description = $data['description'];
    $page->keywords = $data['keywords'];
    $page->callout = $data['callout'];
    $page->language = $data['language'];
    $page->direction = $data['direction'];

    $page->save($site, $user);

    return TRUE;

  }

  /**
   * Removes a page
   *
   * @param {id} $id
   * @return Response
   */
  public function remove($id){

    // remove the page and fragment
    $page = app()->basePath().'/public/sites/'.$id.'/'.$this->url.'.html';
    $name = $new_name = str_replace('/', '.', $this->url);
    $fragment = app()->basePath().'/public/sites/'.$id.'/fragments/page/'.$name.'.html';

    unlink($page);
    unlink($fragment);

    // remove the page from JSON
    $json_file = app()->basePath().'/public/sites/'.$id.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $pages = json_decode($json, true);
      $i = 0;

      foreach($pages as &$page){

        // remove page
        if($page['url'] == $this->url) {
          unset($pages[$i]);
        }

        $i++;

      }

      // save pages
      file_put_contents($json_file, json_encode($pages, JSON_PRETTY_PRINT));

    }

    return TRUE;

  }

  /**
   * Saves a page
   *
   * @param {string} $url url of page
   * @return Response
   */
  public function save($site, $user) {

    // set full file path
    $file = app()->basePath() . '/public/sites/' . $site->id . '/' . $this->url . '.html';

    // open with phpQuery
    $doc = \phpQuery::newDocument(file_get_contents($file));

    // update the html
    $doc['title']->html($this->title);
    $doc['meta[name=description]']->attr('content', $this->description);
    $doc['meta[name=keywords]']->attr('content', $this->keywords);
    $doc['html']->attr('lang', $this->language);
    $doc['html']->attr('dir', $this->direction);
    $doc['meta[name=keywords]']->attr('content', $this->keywords);

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
    $this->photo = $photo;
    $this->thumb = $thumb;

    // save page
    file_put_contents($file, $doc->htmlOuter());

    // set timestamp
    $timestamp = date('Y-m-d\TH:i:s.Z\Z', time());

    // edit the json file
    $json_file = app()->basePath().'/public/sites/'.$site->id.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $pages = json_decode($json, true);

      foreach($pages as &$page){

        // update page
        if($page['url'] == $this->url) {

          $page['title'] = $this->title;
          $page['description'] = $this->description;
          $page['keywords'] = $this->keywords;
          $page['callout'] = $this->callout;
          $page['photo'] = $this->photo;
          $page['thumb'] = $this->thumb;
          $page['layout'] = $this->layout;
          $page['language'] = $this->language;
          $page['direction'] = $this->direction;
          $page['lastModifiedBy'] = $user->email;
          $page['lastModifiedDate'] = $timestamp;

        }

      }

      // save pages
      file_put_contents($json_file, json_encode($pages, JSON_PRETTY_PRINT));

    }

  }

  /**
   * Retrieves page data based on a url
   *
   * @param {string} $url url of page
   * @return Response
   */
  public static function getByUrl($url, $id){

    $file = app()->basePath().'/public/sites/'.$id.'/data/pages.json';

    if(file_exists($file)) {

      $json = file_get_contents($file);

      // decode json file
      $pages = json_decode($json, true);

      foreach($pages as $page){

        if($page['url'] == $url) {

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
   * @param {User} $user
   * @param {string} $id friendly id of site (e.g. site-name)
   * @return Response
   */
  public static function listAll($user, $site){

    $arr = array();

    // get base path for the site
    $json_file = app()->basePath().'/public/sites/'.$site->id.'/data/pages.json';

    if(file_exists($json_file)) {

      $json = file_get_contents($json_file);

      // decode json file
      $arr = json_decode($json, true);

    }
    else{

      // set dir
      $dir = app()->basePath().'/public/sites/'.$site->id;

      // list files
      $files = Utilities::ListFiles($dir, $site->id,
              array('html'),
              array('snippets/',
                    'components/',
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
          $file = app()->basePath() . '/public/sites/' . $site->id . '/' . $file;

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
              'title' => $title,
              'description' => $description,
              'keywords' => $keywords,
              'callout' => $callout,
              'url' => $url,
              'photo' => $photo,
              'thumb' => $thumb,
              'layout' => 'content',
              'language' => $language,
              'direction' => $direction,
              'firstName' => $user->firstName,
              'lastName' => $user->lastName,
              'lastModifiedBy' => $user->email,
              'lastModifiedDate' => $timestamp
          );

          // push to array
          if(substr($url, 0, strlen('.default')) !== '.default') {
            array_push($arr, $data);
          }

      }

      // encode arr
      $content = json_encode($arr, JSON_PRETTY_PRINT);

      // update content
      file_put_contents($json_file, $content);

    }

    return $arr;

  }


}