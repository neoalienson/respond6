<?php

namespace App\Respond\Libraries;

use App\Respond\Models\Site;
use App\Respond\Models\User;
use App\Respond\Models\Page;
use App\Respond\Libraries\Utilities;
use App\Respond\Libraries\Components;

class Publish
{

    /**
     * Pubishes the theme to the site
     *
     * @param {Site} $site
     */
    public static function publishTheme($theme, $site)
    {

        // publish theme files
        $src = app()->basePath() . '/resources/themes/' . $theme;
        $dest = app()->basePath() . '/public/sites/' . $site->id;

        // copy the directory
        Utilities::copyDirectory($src, $dest);

        // copy the private files
        $src = app()->basePath() . '/resources/themes/' . $theme . '/.private';
        $dest = app()->basePath() . '/resources/sites/' . $site->id;

        // copy the directory
        Utilities::copyDirectory($src, $dest);

    }

    /**
     * Pubishes the localse to the site
     *
     * @param {Site} $site
     */
    public static function publishLocales($site)
    {

        // publish theme files
        $src = app()->basePath() . '/resources/locales';
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/locales';

        // copy the directory
        Utilities::copyDirectory($src, $dest);
    }

    /**
     * Pubishes components
     *
     * @param {Site} $site
     */
    public static function publishComponents($site)
    {

        // production
        $dir = app()->basePath() . '/node_modules';

        if (env('APP_ENV') == 'development') {
            $dir = app()->basePath() . '/public/dev';
        }

        // publish components polyfil
        $src = $dir . '/respond-components/bower_components/webcomponentsjs';
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/components/lib';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

        // paths to build file
        $src = $dir . '/respond-components/respond-build.html';
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/components/respond-build.html';

        // make directory
        $dir = app()->basePath() . '/public/sites/' . $site->id . '/components/';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // copy build file
        copy($src, $dest);

    }

    /**
     * Injects site settings the JS to the site
     *
     * @param {Site} $site
     */
    public static function injectSiteSettings($site)
    {
        // create settings
        $settings = array(
            'id' => $site->id,
            'api' => env('APP_URL') . '/api'
        );

        // settings
        $str_settings = json_encode($settings);

        // get site file
        $file = app()->basePath() . '/public/sites/' . $site->id . '/js/respond.site.js';

        if (file_exists($file)) {

            // get contents
            $content = file_get_contents($file);

            $start = 'settings: {';
            $end = '}';

            // remove { }
            $new = str_replace('{', '', $str_settings);
            $new = str_replace('}', '', $new);

            // replace
            $content = preg_replace('#(' . preg_quote($start) . ')(.*?)(' . preg_quote($end) . ')#si', '$1' . $new . '$3', $content);

            // publish updates
            file_put_contents($file, $content);

        }

    }

    /**
     * Republish site components
     *
     * @param {Site} $site
     * @param {Page} $page
     * @param {User} $user
     */
    public static function republishComponents($site, $user) {

      $arr = Page::listAll($user, $site);

      foreach($arr as $item) {

        // get page
        $page = new Page($item);

        $location = app()->basePath().'/public/sites/'.$site->id.'/'.$page->url.'.html';

        // get layout html
        $html = file_get_contents($location);

        // get phpQuery of file
        $doc = \phpQuery::newDocument($html);

        foreach ($doc['[respond-menu], respond-menu'] as $el) {

            $type = pq($el)->attr('type');
            $cssClass = pq($el)->attr('class');

            // get the type
            if ($type != NULL) {

              $attrs = array('type' => $type, 'cssClass' => $cssClass);

              // get the menu HTML
              $html = Components::respondMenu($attrs, $site, $page);

              // get menu HTML
              pq($el)->replaceWith($html);

            }
            /* isset */

        }
        /* foreach */

        foreach ($doc['[respond-form], respond-form'] as $el) {

            $id = pq($el)->attr('id');

            echo('found @ ' . $page->url . ', id='.$id);

            // get the type
            if ($id != NULL) {

              $attrs = array('id' => $id);

              // get the menu HTML
              $html = Components::respondForm($attrs, $site, $page);

              // get menu HTML
              pq($el)->replaceWith($html);

            }
            /* isset */

        }
        /* foreach */

        // publish
        $html = $doc->htmlOuter();
        file_put_contents($location, $html);

      }

    }

}