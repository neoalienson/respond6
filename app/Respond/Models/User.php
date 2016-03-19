<?php

namespace App\Respond\Models;

use App\Respond\Models\Site;
use App\Respond\Libraries\Utilities;
use Symfony\Component\Yaml\Parser;

/**
 * Models a user
 */
class User {

  public $Email;
  public $Password;
  public $FirstName;
  public $LastName;
  public $Role;
  public $Language;
  public $Photo;
  public $Token;

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
   * Gets a user for a given siteid, email
   *
   * @param {string} $siteId
   * @param {string} $email
   * @return {User}
   */
	public static function GetByEmail($siteId, $email){

    $site = Site::GetById($siteId);

    foreach($site->Users as $user) {

      if($user['Email'] == $email) {

        return new User($user);

      }

    }

    return NULL;

	}

	/**
   * Gets a user for a given siteid, token
   *
   * @param {string} $siteId
   * @param {string} $token
   * @return {User}
   */
	public static function GetByToken($siteId, $token){

    $site = Site::GetById($siteId);

    foreach($site->Users as $user) {

      if($user['Token'] == $token) {

        return new User($user);

      }

    }

    return NULL;

	}

	/**
   * Gets a site for a given Id
   *
   * @param {string} $id the ID for the user
   * @return {Site}
   */
	public static function GetByEmailPassword($siteId, $email, $password){

    $site = Site::GetById($siteId);

    foreach($site->Users as $user) {

      if($user['Email'] == $email) {

        $user = new User($user);

        $hash = $user->Password;

        if(password_verify($password, $hash)) {
            return $user;
        }
        else {
            return NULL;
        }

      }

    }

    return NULL;

	}





}