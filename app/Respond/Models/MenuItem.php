<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Menu;

/**
 * Models a menu item
 */
class MenuItem {

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
   * lists all menu items
   *
   * @param {files} $data
   * @return {array}
   */
  public static function listAll($id, $siteId) {

    $file = app()->basePath().'/public/sites/'.$siteId.'/data/menus/'.$id.'.json';
    
    $arr = array();

    if(file_exists($file)) {
      $json = json_decode(file_get_contents($file), true);
      
      $arr = $json['items'];
    }

    return $arr;

  }
  
  /**
   * Adds a menu item
   *
   * @param {files} $data
   * @return {array}
   */
  public static function add($html, $cssClass, $isNested, $url, $menuId, $siteId) {
    
    $menu = Menu::getById($menuId, $siteId);
    
    $item = array(
      'html' => $html,
      'cssClass' => $cssClass,
      'isNested' => $isNested,
      'url' => $url
      );
    
    array_push($menu->items, $item);
      
    $menu->save($siteId);
    
    return $item;
     
  }

}