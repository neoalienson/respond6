<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

/**
 * Models submission
 */
class Submission {

  public $id;
  public $name;
  public $url;
  public $formId;
  public $date;
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
   * lists all settings
   *
   * @param {files} $data
   * @return {array}
   */
  public static function listAll($siteId) {

    $file = app()->basePath().'/resources/sites/'.$siteId.'/submissions.json';

    if(file_exists($file)) {
      return json_decode(file_get_contents($file), true);
    }
    else {
      return array(); 
    }
    

  }

  /**
   * Saves a submission
   *
   * @param {string} $id the ID of the site
   * @return {Site}
   */
  public function save($siteId) {

    // defaults
    $dir = app()->basePath().'/resources/sites/'.$siteId.'/';
    $is_match = false;

    $submissions = Submission::listAll($siteId);

    // push user
    array_push($submissions, (array)$this);

    // save users
    $json = json_encode($submissions, JSON_PRETTY_PRINT);

    // save site.json
    Utilities::saveContent($dir, 'submissions.json', $json);

    return;

  }

}