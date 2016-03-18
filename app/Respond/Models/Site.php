<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use App\Respond\Libraries\Publish;

/**
 * Models a site
 */
class Site {

  public $Id;
  public $Name;
  public $Logo;
  public $AltLogo;
  public $Icon;
  public $Color;
  public $Domain;
  public $Theme;
  public $Email;
  public $TimeZone;
  public $Language;
  public $Direction;
  public $ShowCart;
  public $ShowSearch;
  public $Users;

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
   * Saves a user
   *
   * @param {string} $id the ID for the user
   * @return {Site}
   */
  public function SaveUser($user) {

    $is_match = false;

    foreach($this->Users as &$item) {

      // check email
      if($item['Email'] == $user->Email) {

        // update user
        $is_match = true;
        $item = (array)$user;

        // save site
        $this->Save();

        return;

      }

    }

    // push user
    array_push($this->Users, $user);

    // save site
    $this->Save();

    return;

  }

  /**
   * Saves a site
   *
   * @param {string} $id the ID for the user
   * @return {Site}
   */
  public function Save() {

    $dumper = new Dumper();

    $dir = app()->basePath().'/resources/sites/'.$this->Id.'/';

    $json = json_encode($this);
    $arr = json_decode($json, true);

    $yaml = $dumper->dump($arr, 3);

    // save site.yaml
    Utilities::SaveContent($dir, 'site.yaml', $yaml);

  }

  /**
   * Gets a site for a given Id
   *
   * @param {string} $id the ID for the user
   * @return {Site}
   */
	public static function GetById($id) {

    $yaml = new Parser();
    $file = app()->basePath().'/resources/sites/'.$id.'/site.yaml';

    if(file_exists($file)) {

      try {
        $arr = $yaml->parse(file_get_contents($file));

        return new Site($arr);
      }
      catch (ParseException $e) {
        return NULL;
      }

    }
    else {
      return NULL;
    }


	}

	/**
   * Gets a site for a given Id
   *
   * @param {string} $id
   * @return {Site}
   */
	public static function IsIdUnique($id) {

    $file = app()->basePath().'/public/sites/'.$id;

    if(file_exists($file)) {

      return FALSE;
    }
    else {

      return TRUE;

    }

	}

	/**
   * Gets a site for a given Id
   *
   * @param {string} $id the ID for the user
   * @return {Site}
   */
	public static function Create($name, $theme, $email, $password) {

	  // create an id
	  $id = strtolower($name);

    // replaces all spaces with hyphens
    $id = str_replace(' ', '-', $id);

    // replaces all spaces with hyphens
    $id = $new_id =  preg_replace('/[^A-Za-z0-9\-]/', '', $id);

    // find a unique $id (e.g. myid, myid1, myid2, etc.)
    $x = 1;
    $folder = app()->basePath().'/public/sites/'.$id;

    while(file_exists($folder) === TRUE) {

      // increment id and folder
      $new_id = $id.$x;
      $folder = app()->basePath().'/public/sites/'.$new_id;
      $x++;

    }

    // set id to new_id
    $id = $new_id;

    // set defaults
    $timeZone = 'America/Chicago';
    $language = env('DEFAULT_LANGUAGE');
    $direction = env('DEFAULT_DIRECTION');
    $domain = env('APP_URL').'/sites/'.$id;

    // create a site
    $site_arr = array(
      'Id' => $id,
      'Name' => $name,
      'Logo' => 'sample-logo.png',
      'AltLogo' => 'sample-logo.png',
      'Icon' => '',
      'Color' => '',
      'Domain' => $domain,
      'Theme' => $theme,
      'Email' => $email,
      'TimeZone' => $timeZone,
      'Language' => $language,
      'Direction' => $direction,
      'ShowCart' => true,
      'ShowSearch' => true,
      'Users' => array()
    );

  	$site = new Site($site_arr);

    // create a user
    $user_arr = array(
      'Email' => $email,
      'Password' => password_hash($password, PASSWORD_DEFAULT),
      'Name' => 'New User',
      'Role' => 'Admin',
      'Language' => $language,
      'Photo' => '',
      'Token' => ''
    );

    $user = new User($user_arr);

    // this saves both the user and the site
    $site->SaveUser($user);

    // publish theme
    Publish::PublishTheme($site);

    // inject settings
    Publish::InjectSiteSettings($site);

     // publish site
    Publish::PublishThemeMenus($site);

    // publish default content
    Publish::PublishDefaultContent($site, $user);

    // publish components
    Publish::PublishComponents($site);

    // return site information
    return array(
      'Id' => $id,
      'Name' => $name,
      'Domain' => $domain
      );

  }


}