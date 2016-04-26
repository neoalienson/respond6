<?php

namespace App\Respond\Libraries;

class Components
{

  /**
   * Pre-processes content before it goes to edit
   *
   * @param {array} $attrs
   * @param {Site} $site
   * @param {Page} $page
   */
  public static function preProcess($doc) {

    /*
    // find forms
    $els = $doc['[respond-form]'];

    // replace the form code with the component display
    foreach($els as $el) {
      $id = pq($el)->attr('id');

      $html = '<respond-form id="'.$id.'"></respond-form>';

      pq($el)->replaceWith($html);
    }

    */

  }


  /**
   * Generates HTML for <respond-menu type="menu-type"></respond-menu>
   *
   * @param {array} $attrs
   * @param {Site} $site
   * @param {Page} $page
   */
  public static function respondMenu($attrs, $site, $page) {

    $type = $attrs['type'];
    $cssClass = $attrs['cssClass'];

    // set html extension
    $ext = '.html';

    if (env('FRIENDLY_URLS') === true) {
        $ext = '';
    }

    // get json for menu
    $file = app()->basePath() . '/public/sites/' . $site->id . '/data/menus/' . $type . '.json';

    $menu = '';

    if (file_exists($file)) {

        // init menu
        $menu = '<ul';

        // set class if applicable
        if ($cssClass != '') {
            $menu .= ' class="' . $cssClass . '" respond-menu type="' . $type . '">';
        } else {
            $menu .= ' respond-menu type="' . $type . '">';
        }

        // get items for type
        $json = json_decode(file_get_contents($file), true);

        // get items
        $menuItems = $json['items'];

        $i = 0;
        $parent_flag = false;
        $new_parent = true;

        // walk through items
        foreach ($menuItems as $menuItem) {

            $html = $menuItem['html'];
            $cssClass = $menuItem['cssClass'];
            $url = $menuItem['url'];
            $isNested = $menuItem['isNested'];

            // set active
            if ($page->url === $url) {
                $cssClass .= ' active';
            }

            // check for new parent
            if (isset($arr[$i + 1])) {
                if ($menuItems[$i + 1]['isNested'] == true && $new_parent == true) {
                    $parent_flag = true;
                }
            }

            $menu_root = '/';

            // check for external links
            if (strpos($url, 'http') !== false) {
                $menu_root = '';
            }

            if ($new_parent == true && $parent_flag == true) {
                $menu .= '<li class="dropdown">';
                $menu .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $menuItem['html'] . ' <span class="caret"></span></a>';
                $menu .= '<ul class="dropdown-menu">';
                $new_parent = false;
            } else {
                $menu .= '<li' . $cssClass . '>';
                $menu .= '<a href="' . $url . $ext . '">' . $menuItem['html'] . '</a>';
                $menu .= '</li>';
            }

            // end parent
            if (isset($menuItems[$i + 1])) {
                if ($menuItems[$i + 1]['isNested'] == false && $parent_flag == true) {
                    $menu .= '</ul></li>'; // end parent if next item is not nested
                    $parent_flag = false;
                    $new_parent = true;
                }
            } else {
                if ($parent_flag == true) {
                    $menu .= '</ul></li>'; // end parent if next menu item is null
                    $parent_flag = false;
                    $new_parent = true;
                }
            }

            $i = $i + 1;
        }

        $menu .= '</ul>';

      }

      return $menu;

  }

  /**
   * Generates HTML for <respond-form id="form-id"></respond-menu>
   *
   * @param {array} $attrs
   * @param {Site} $site
   * @param {Page} $page
   */
  public static function respondForm($attrs, $site, $page) {


    /*
    $id = $attrs['id'];

    // get json for menu
    $file = app()->basePath() . '/public/sites/' . $site->id . '/data/forms/' . $id . '.json';

    $form = '';

    if (file_exists($file)) {

        $json = json_decode(file_get_contents($file), true);

        // setup form
        $form = '<form id="' . $id . '" respond-form class="'.$json['cssClass'].'">';

        // get fields
        $fields = $json['fields'];

        foreach ($fields as $field) {

          $label = $field['label'];
          $type = $field['type'];
          $id = $field['id'];
          $placeholder = $field['placeholder'];
          $helperText = $field['helperText'];
          $required = '';
          $options = explode(',', $field['options']);

          if ($field['required'] === TRUE) {
            $required = ' required';
          }

          // create label
          $form .= '<label for="' . $id . '">' . $label . '</label>';

          // text
          if($type=='text'){
    				$form .= '<input id="' . $id . '" name="' . $id . '" type="text" class="form-control" placeholder="'. $placeholder. '"' . $required . '>';
    			}

    			// email
          if($type=='email'){
    				$form .= '<input id="' . $id . '" name="' . $id . '" type="email" class="form-control" placeholder="'. $placeholder. '"' . $required . '>';
    			}

    			// textarea
    			if($type=='textarea'){
    				$form .= '<textarea id="' . $id . '" name="' . $id . '" class="form-control" placeholder="'. $placeholder. '"' . $required . '></textarea>';
    			}

    			// select
    			if($type=='select'){
    				$form .= '<select id="' . $id . '" name="' . $id . '" class="form-control" placeholder="'. $placeholder. '"' . $required . '>';

    				foreach ($options as $option) {
      				$form .= '<option value="' . $option . '">' . $option .'</option>';
    				}

    				$form .= '</select>';
    			}

        }

        // close form
        $form .= '</form>';

      }

      return $form;

      */

  }

  /**
   * Generates HTML for <respond-content url="page/test"></respond-content>
   *
   * @param {array} $attrs
   * @param {Site} $site
   * @param {Page} $page
   */
  public static function respondContent($attrs, $site, $page) {

    $url = $attrs['url'];

    // replace the / with a period
    $url = str_replace('/', '.', $url);
    $url .= '.html';

    // default is blank
    $html = '';

    // get location of content html
    $dest = app()->basePath() . '/public/sites/' . $site->id;
    $content_dest = $dest . '/fragments/page/' . $url;

    // get contents
    if (file_exists($content_dest)) {
        $html = file_get_contents($content_dest);
    }

    return $html;

  }


}