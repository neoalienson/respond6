<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

/**
 * Models a form
 */
class Form {

  public $id;
  public $name;
  public $cssClass;
  public $fields;

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
   * lists all files
   *
   * @param {files} $data
   * @return {array}
   */
  public static function listAll($siteId) {

    $dir = app()->basePath().'/public/sites/'.$siteId.'/data/forms/';
    $exts = array('json');

    $files = Utilities::listFiles($dir, $siteId, $exts);
    $arr = array();

    foreach($files as $file) {

      $path = app()->basePath().'/public/sites/'.$siteId.'/'.$file;

      if(file_exists($path)) {

        $json = json_decode(file_get_contents($path), true);

        $id = str_replace('.json', '', $file);
        $id = str_replace('data/forms/', '', $id);
        $id = str_replace('/', '', $id);

        array_push($arr, array(
          'id' => $id,
          'name' => $json['name'],
          'cssClass' => $json['cssClass']
          ));

      }

    }

    return $arr;

  }


  /**
   * Gets by id
   *
   * @param {string} $name
   * @param {string} $siteId site id
   * @return Response
   */
  public static function getById($id, $siteId) {

    $file = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$id.'.json';

    $items = array();

    if(file_exists($file)) {

      $json = json_decode(file_get_contents($file), true);

      return new Form($json);

    }

    return NULL;

  }

  /**
   * Adds a form
   *
   * @param {string} $name
   * @param {string} $cssClass
   * @param {string} $siteId site id
   * @return Response
   */
  public static function add($name, $cssClass, $siteId) {

    // build a name
	  $id = strtolower($name);

    // replaces all spaces with hyphens
    $id = str_replace(' ', '-', $id);

    // replaces all spaces with hyphens
    $id = $new_id =  preg_replace('/[^A-Za-z0-9\-]/', '', $id);

    // find a unique $id (e.g. myid, myid1, myid2, etc.)
    $x = 1;
    $folder = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$id.'.json';

    while(file_exists($folder) === TRUE) {

      // increment id and folder
      $new_id = $id.$x;
      $folder = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$new_id.'.json';
      $x++;

    }

    // set id to new_id
    $id = $new_id;

    // get file
    $file = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$new_id.'.json';

    $items = array();

    if(!file_exists($file)) {

      // get form
      $form = new Form(array(
        'id' => $new_id,
        'name' => $name,
        'cssClass' => $cssClass,
        'fields' => array()
      ));

      file_put_contents($file, json_encode($form, JSON_PRETTY_PRINT));

      return $form;

    }

    return NULL;

  }

  /**
   * Saves a form
   *
   * @param {string} $name
   * @param {string} $siteId site id
   * @return Response
   */
  public function save($siteId) {

    // get file
    $file = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$this->id.'.json';

    if(file_exists($file)) {

      file_put_contents($file, json_encode($this, JSON_PRETTY_PRINT));


      return TRUE;

    }

    return NULL;

  }


  /**
   * Removes a form
   *
   * @param {name} $name
   * @return Response
   */
  public function remove($siteId) {

    $file = app()->basePath().'/public/sites/'.$siteId.'/data/forms/'.$this->id.'.json';

    if(file_exists($file)) {
      unlink($file);

      return TRUE;
    }

    return FALSE;

  }

}