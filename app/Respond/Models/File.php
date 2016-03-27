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

  public static function ListAll() {



  }

}