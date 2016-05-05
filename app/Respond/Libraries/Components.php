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

    $id = $attrs['id'];

    // get json for menu
    $file = app()->basePath() . '/public/sites/' . $site->id . '/data/forms/' . $id . '.json';

    $form = '';

    if (file_exists($file)) {

        $json = json_decode(file_get_contents($file), true);

        // setup form
        $html = '<respond-form id="' . $id . '">';

        // get fields
        $fields = $json['fields'];

        foreach ($fields as $field) {

          $label = $field['label'];
          $type = $field['type'];
          $id = $field['id'];
          $placeholder = $field['placeholder'];
          $helperText = $field['helperText'];
          $required = $field['required'];
          $options = $field['options'];

          // set fields
          $html .= '<respond-form-field fieldid="'.$id.
                    '" type="'.$type.
                    '" label="'.$label.
                    '" required="'.$required.
                    '" helper="'.$helperText.
                    '" placeholder="'.$placeholder.
                    '" options="'.$options.'"></respond-form-field>';

        }

        // close form
        $html .= '</respond-form>';

      }

      return $html;


  }

  /**
   * Generates HTML for <respond-gallery id="gallery-id"></respond-gallery>
   *
   * @param {array} $attrs
   * @param {Site} $site
   * @param {Page} $page
   */
  public static function respondGallery($attrs, $site, $page) {

    // id of the gallery
    $id = $attrs['id'];

    // get json for menu
    $file = app()->basePath() . '/public/sites/' . $site->id . '/data/galleries/' . $id . '.json';

    $form = '';

    if (file_exists($file)) {

        $json = json_decode(file_get_contents($file), true);

        // setup form
        $html = '<respond-gallery id="' . $id . '">';

        // get fields
        $images = $json['images'];

        foreach ($images as $image) {

          $html .= '<img src="'. $image['url'].'" data-caption="'.$image['caption'].'" data-thumb="'.$image['thumb'].'" />';

        }

        // close form
        $html .= '</respond-gallery>';

      }

      return $html;

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