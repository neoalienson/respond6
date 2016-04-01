<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

/**
 * Models a menu
 */
class Menu {

  public $name;

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
  public static function listAll($id) {

    $dir = app()->basePath().'/public/sites/'.$id.'/data/menus/';
    $exts = array('json');

    $files = Utilities::listFiles($dir, $id, $exts);
    $arr = array();

    foreach($files as $file) {

      $name = str_replace('.json', '', $file);
      $name = str_replace('data/menus/', '', $name);
      $name = str_replace('/', '', $name);

      array_push($arr, array('name'=>$name));

    }

    return $arr;

  }


  /**
   * Gets by name
   *
   * @param {string} $name
   * @param {string} $id site id
   * @return Response
   */
  public static function getByName($name, $id){

    $file = app()->basePath().'/public/sites/'.$id.'/data/menus/'.$name.'.json';

    $items = array();

    if(!file_exists($dest)) {

      return new Menu(array(
        'name' => $name
      ));;

    }

    return NULL;

  }

  /**
   * Adds a menu
   *
   * @param {string} $name
   * @param {string} $id site id
   * @return Response
   */
  public static function add($name, $id){

    $file = app()->basePath().'/public/sites/'.$id.'/data/menus/'.$name.'.json';

    $items = array();

    if(!file_exists($dest)) {

      file_put_contents($file, json_encode($items));

      return new Menu(array(
        'name' => $name
      ));;

    }

    return NULL;

  }


  /**
   * Removes a menu
   *
   * @param {name} $name
   * @return Response
   */
  public function remove($id){

    $file = app()->basePath().'/public/sites/'.$site->id.'/data/menus/'.$this->name.'.json';

    if(file_exists($file)) {
      unlink($file);

      return TRUE;
    }

    return FALSE;

  }

}