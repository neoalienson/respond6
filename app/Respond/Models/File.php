<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;

use App\Respond\Libraries\Publish;

/**
 * Models a file
 */
class File {

  public $FileName;

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
   * Lists images for the site
   *
   * @param {sttring} $siteId
   */
  public static function ListImages($siteId) {

    $dir = app()->basePath().'/public/sites/'.$siteId.'/files';

    // list files
    $arr = Utilities::ListFiles($dir, $siteId,
            array('png', 'jpg', 'gif', 'svg'),
            array('thumb/',
                  'thumbs/'));

    return $arr;

  }

  /**
   * Lists files for the site
   *
   * @param {string} $siteId
   */
  public static function ListFiles($siteId) {

    $dir = app()->basePath().'/public/sites/'.$siteId.'/files';

    // list allowed types
    $exts = explode(',', env('ALLOWED_FILETYPES'));

    // list files
    $arr = Utilities::ListFiles($dir, $siteId,
            $exts,
            array('thumb/',
                  'thumbs/'));

    return $arr;

  }

}