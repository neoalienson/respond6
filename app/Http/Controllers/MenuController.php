<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Publish;

use App\Respond\Models\Site;
use App\Respond\Models\User;

use App\Respond\Models\Menu;

class MenuController extends Controller
{

  /**
   * Lists all menus for a site
   *
   * @return Response
   */
  public function listAll(Request $request)
  {

    // get request data
    $email = $request->input('auth-email');
    $id = $request->input('auth-id');

    // list pages in the site
    $arr = Menu::listAll($id);

    return response()->json($arr);

  }

  /**
   * Adds the menu
   *
   * @return Response
   */
  public function add(Request $request)
  {
    // get request data
    $email = $request->input('auth-email');
    $id = $request->input('auth-id');

    // get url, title and description
    $name = $request->json()->get('name');

    // add a menu
    $menu = Menu::add($name, $id);

    if($menu !== NULL) {
     // return OK
     return response('OK, menu added at = '.$menu->name, 200);
    }

    return response('Menu already exists', 400);

  }

  /**
   * Removes the menu
   *
   * @return Response
   */
  public function remove(Request $request)
  {
    // get request data
    $email = $request->input('auth-email');
    $id = $request->input('auth-id');

    // get name
    $name = $request->json()->get('name');

    $menu = Menu::getByName($name, $id);

    if($menu !== NULL) {
      $menu->remove($id);

      // return OK
      return response('OK, menu removed at = '.$page->name, 200);
    }

    return response('Menu not found', 400);

  }

}