<?php

namespace App\Respond\Database;

/**
 * Models the User database
 */
class User{

  /**
   * Sets a token that enables a user to reset his/her password
   *
   * @param {string} $userId the ID for the user
   * @return {string} new token
   */
	public static function SetToken($userId){

  	$token = $userId.uniqid();

    $db = app('db')->connection()->getPdo();

    $q = "UPDATE Users SET Token = ? WHERE UserId=?";

    $result = app('db')->update($q, [$token, $userId]);

    return $token;

	}

	/**
   * Edits the password for a user
   * #ref: https://gist.github.com/nikic/3707231
   *
   * @param {string} $userId the ID for the user
   * @param {string} $password new password
   */
	public static function EditPassword($userId, $password){

    $db = app('db')->connection()->getPdo();

    if($password != "temppassword"){

      $hash = password_hash($password, PASSWORD_DEFAULT);

      echo $hash;

      $q = "UPDATE Users SET Token = '',
            Password = ?
            WHERE UserId = ?";

      $result = app('db')->update($q, [$hash, $userId]);

    }

	}

	/**
   * Retrieves a user by a token
   *
   * @param {string} $email the email of the user
   * @param {string} $siteId the id fo the site
   * @param {string} $password the password of the user
   * @return {User}
   */
	public static function GetByToken($token, $siteId){

    $db = app('db')->connection()->getPdo();

    $q = "SELECT UserId, Email, Password, FirstName, LastName, PhotoUrl,
            		Role, Language, IsActive, SiteAdmin, SiteId, Created
        			FROM Users WHERE Token=? AND SiteId=?";

    $result = app('db')->select($q, [$token, $siteId]);

    if(sizeof($result) == 1) {

      $user = $result[0];

      return $user;

    }
    else {
      return NULL;
    }


	}

	/**
   * Retrieves a user by email and password
   *
   * @param {string} $email the email of the user
   * @param {string} $siteId the id fo the site
   * @param {string} $password the password of the user
   * @return {User}
   */
	public static function GetByEmailPassword($email, $siteId, $password){

    $db = app('db')->connection()->getPdo();

    $q = "SELECT UserId, Email, Password, FirstName, LastName, PhotoUrl,
          Role, Language, IsActive, SiteAdmin, SiteId, Created, Token
          FROM Users WHERE Email=? AND SiteId=? AND IsActive = 1";

    $result = app('db')->select($q, [$email, $siteId]);

    if(sizeof($result) == 1) {

      $user = $result[0];

      $hash = $user->Password;

      if (password_verify($password, $hash)) {
          return $user;
      }
      else {
          return NULL;
      }

    }
    else {
      return NULL;
    }


	}

  /**
   * Retrieves a user for a given email and site
   *
   * @param {string} $email the email of the user
   * @param {string} $siteId the id fo the site
   * @return {User}
   */
	public static function GetByEmail($email, $siteId){

    $db = app('db')->connection()->getPdo();

    $q = "SELECT UserId, Email, Password, FirstName, LastName, PhotoUrl,
          Role, Language, IsActive, SiteAdmin, SiteId, Created, Token
          FROM Users WHERE Email=? AND SiteId=? AND IsActive = 1";

    $result = app('db')->select($q, [$email, $siteId]);

    if(sizeof($result) == 1) {

      $user = $result[0];

      return $user;

    }
    else {
      return NULL;
    }


	}



}

?>