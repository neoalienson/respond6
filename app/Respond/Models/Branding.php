<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

/**
 * Models branding
 */
class Branding {

  public $id;
  public $label;
  public $description;
  public $type;
  public $value;

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
   * lists all branding settings
   *
   * @param {files} $data
   * @return {array}
   */
  public static function listAll($siteId) {

    $file = app()->basePath().'/resources/sites/'.$siteId.'/branding.json';

    return json_decode(file_get_contents($file), true);

  }

  /**
   * Saves all settings
   *
   * @param {string} $name
   * @param {string} $siteId site id
   * @return Response
   */
  public static function saveAll($settings, $siteId) {

    // get file
    $file = app()->basePath().'/resources/sites/'.$siteId.'/branding.json';

    // get settings
    if(file_exists($file)) {

      file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT));

      return TRUE;

    }


    return FALSE;

  }

}