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
    public static function publishTheme($site)
    {

        // publish theme files
        $src = app()->basePath() . '/resources/themes/' . $site->theme;
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme;

        // copy the directory
        Utilities::copyDirectory($src, $dest);

        // publish CSS
        Publish::publishThemeCSS($site);

        // compress CSS
        Publish::compressCSS($site);

        // publish JS
        Publish::publishThemeJS($site);

        // publish JS
        Publish::publishThemeFiles($site);

    }

    /**
     * Pubishes the theme files to the site
     *
     * @param {Site} $site
     */
    public static function publishThemeFiles($site)
    {

        // publish js files
        $src = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme . '/files';
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/files';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

    }

    /**
     * Pubishes the theme JS to the site
     *
     * @param {Site} $site
     */
    public static function publishThemeJS($site)
    {

        // publish js files
        $src = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme . '/js';
        $dest = app()->basePath() . '/public/sites/' . $site->id . '/js';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

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
            'api' => env('APP_URL') . '/api',
            'showCart' => $site->showCart,
            'showSearch' => $site->showSearch
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
     * Injects Payment settings
     *
     * @param {Site} $site
     */
    public static function injectPaymentSettings($site)
    {

        // paypal
        $file = app()->basePath() . '/resources/sites/' . $id . '/paypal.json';

        if (file_exists($file)) {

            $arr = json_decode(file_get_contents($file));

            // settings
            $str_settings = 'paypal: {' . json_encode($settings, true) . '}';

            // get site file
            $file = app()->basePath() . '/public/sites/' . $site->id . '/js/respond.site.js';

            if (file_exists($file)) {

                // get contents
                $content = file_get_contents($file);

                $start = 'payment: [';
                $end = ']';

                // replace
                $content = preg_replace('#(' . preg_quote($start) . ')(.*?)(' . preg_quote($end) . ')#si', '$1' . $new . '$3', $content);

                // publish updates
                file_put_contents($file, $content);

            }

        }

    }

    /**
     * Pubishes the css to the site
     *
     * @param {Site} $site
     */
    public static function publishThemeCSS($site)
    {

        // publish css files
        $src = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme . '/css';
        $dest = $dir = app()->basePath() . '/public/sites/' . $site->id . '/css';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

    }

    /**
     * Pubishes the menus from the theme
     *
     * @param {Site} $site
     */
    public static function publishThemeMenus($site)
    {

        // publish css files
        $src = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme . '/menus';
        $dest = $dir = app()->basePath() . '/public/sites/' . $site->id . '/data/menus';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

    }

    /**
     * Pubishes the forms from the theme
     *
     * @param {Site} $site
     */
    public static function publishThemeForms($site)
    {

        // publish css files
        $src = app()->basePath() . '/public/sites/' . $site->id . '/themes/' . $site->theme . '/forms';
        $dest = $dir = app()->basePath() . '/public/sites/' . $site->id . '/data/forms';

        // copy the directory
        Utilities::copyDirectory($src, $dest);

    }

    /**
     * Pubishes the css to the site
     *
     * @param {Site} $site
     */
    public static function compressCSS($site)
    {

        $dir = app()->basePath() . '/public/sites/' . $site->id . '/css';

        // combine and compress css
        $files = scandir($dir);
        $combined_css = '';

        // walk through files and combine and minify css
        foreach ($files as $value) {

            if (is_file("$dir/$value")) {

                $file = "$dir/$value";
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                if ($ext == 'css') {

                    $css = file_get_contents($file);

                    // compress css, #ref: http://manas.tungare.name/software/css-compression-in-php/
                    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
                    $css = str_replace(': ', ':', $css);
                    $css = str_replace(array(
                        "\r\n",
                        "\r",
                        "\n",
                        "\t",
                        '  ',
                        '    ',
                        '    '
                    ), '', $css);

                    // add to combined css
                    $combined_css .= $css;

                }

            }

        }

        // create combined css
        file_put_contents(app()->basePath() . '/public/sites/' . $site->id . '/css/respond.min.css', $combined_css);

    }

    /**
     * Pubishes the menus to the site
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
     * Generates default content for the site
     *
     * @param {Site} $site
     */
    public static function publishDefaultContent($site, $user)
    {

        $file = app()->basePath() . '/resources/themes/' . $site->theme . '/theme.json';
        $timestamp = gmdate('D M d Y H:i:s O', time());

        if (file_exists($file)) {

            $arr = json_decode(file_get_contents($file), true);

            foreach ($arr['pages'] as $page) {

                $url = $page['url'];
                $title = $page['title'];
                $description = $page['description'];
                $source = $page['source'];
                $layout = $page['layout'];

                // get source
                $source = app()->basePath() . '/resources/themes/' . $site->theme . '/' . $page['source'];

                if (file_exists($source)) {

                    $content = file_get_contents($source);

                    // add page
                    $data = array(
                        'title' => $title,
                        'description' => $description,
                        'keywords' => '',
                        'callout' => '',
                        'url' => $url,
                        'layout' => $layout,
                        'language' => 'en',
                        'lastModifiedBy' => $user->email,
                        'lastModifiedDate' => $timestamp
                    );

                    // add a page
                    Page::add($data, $site, $user, $content);

                }

            }

        }

    }

    /**
     * Update menus for site
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

        foreach ($doc['[respond-menu]'] as $el) {

            $type = pq($el)->attr('type');
            $cssClass = pq($el)->attr('class');

            // get the type
            if ($type != NULL) {

              // get the menu HTML
              $html = Components::respondMenu($type, $cssClass, $page->url, $site->id);

              // get menu HTML
              pq($el)->replaceWith($html);

            }
            /* isset */

            // get updated html
            $html = $doc->htmlOuter();

            // publish
            file_put_contents($location, $html);

        }
        /* foreach */

      }

    }


    /**
     * Publishes all pages for a site
     *
     * @param {Site} $site
     * @param {Page} $page
     * @param {User} $user
     */
    public static function publishAllPages($site, $user) {

      $arr = Page::listAll($user, $site);

      foreach($arr as $item) {

        $page = new Page($item);

        Publish::publishPage($site, $page, $user);

      }

    }

    /**
     * Publishes the page
     *
     * @param {Site} $site
     * @param {Page} $page
     * @param {User} $user
     */
    public static function publishPage($site, $page, $user)
    {

        $dest = app()->basePath() . '/public/sites/' . $site->id;

        $imageurl = $dest . 'files/';

        $url = '';
        $file = $page->url;

        // set base
        $base = '';

        // explode url by '/'
        $parts = explode('/', $page->url);

        // set base based on the depth
        if (sizeof($parts) == 1) {
            $base = '';
        } else {
            $base = str_repeat('../', sizeof($parts) - 1);
        }

        // generate default
        $html = '';
        $content = '';

        // get layout from theme
        $layout = $dest . '/themes/' . $site->theme . '/layouts/' . $page->layout . '.html';

        // get fragment html
        if (file_exists($layout)) {

            // get layout html
            $html = file_get_contents($layout);

            // apply mustache syntax to the layout
            $html = Publish::applyMustacheSyntax($html, $site, $page, $user);

            // get phpQuery of file
            $doc = \phpQuery::newDocument($html);

            // set show-cart, show-settings, show-languages, show-login
            if ($site->showCart == 1) {
                $doc['body']->addClass('show-cart');
            }

            if ($site->showSearch == 1) {
                $doc['body']->addClass('show-search');
            }

        }

        if ($doc !== NULL) {

            // generate the [render=publish] components
            $doc = Publish::generateRenderAtPublish($doc, $site, $page);

            // get html
            $html = $doc->htmlOuter();

            // applies the mustache syntax
            $html = Publish::applyMustacheSyntax($html, $site, $page, $user);

        } else {
            $html = '';
        }

        // update base
        $html = str_replace('<base href="/">', '<base href="' . $base . '">', $html);

        // file location
        $location = $dest . '/' . $file . '.html';

        $dir = dirname($location);

        // make directory
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // save the publishe file
        file_put_contents($location, $html);

        return $location;

    }

    /**
     * Generates the render=publish tags
     *
     * @param {PhpQueryDoc} $doc
     * @param {Site} $site
     * @param {Page} $page
     */
    public static function generateRenderAtPublish($doc, $site, $page)
    {

        foreach ($doc['respond-menu[render=publish]'] as $el) {

            $type = pq($el)->attr('type');
            $cssClass = pq($el)->attr('class');

            // get the type
            if ($type != NULL) {

              // get the menu HTML
              $html = Components::respondMenu($type, $cssClass, $page->url, $site->id);

              // get menu HTML
              pq($el)->replaceWith($html);

            }
            /* isset */

        }
        /* foreach */

        // replace content where render is set to publish
        foreach ($doc['respond-content[render=publish]'] as $el) {

            $url = pq($el)->attr('url');

            // get the url
            if (isset($url)) {

                $html = Components::respondContent($url, $site->id);

                pq($el)->replaceWith($html);

            }
            /* isset */

        }
        /* foreach */

        // recursively call generate if needed
        if (sizeof($doc['[render=publish]']) > 0) {
            $doc = Publish::generateRenderAtPublish($doc, $site, $page);
        }

        return $doc;

    }

    /**
     * Generates the render=publish tags
     *
     * @param {string} $html
     * @param {Site} $site
     * @param {Page} $page
     * @param {User} $user
     */
    public static function applyMustacheSyntax($html, $site, $page, $user)
    {

        // meta data
        $photo = '';
        $name = '';
        $lastModifiedDate = $page->lastModifiedDate;

        // replace last modified
        if ($page->lastModifiedBy != NULL) {

            // set user infomration
            if ($user != NULL) {
                $photoUrl = 'files/' . $user->photo;
                $name = $user->firstName . ' ' . $user->lastName;
            }

        }

        // set page information
        $html = str_replace('{{page.PhotoUrl}}', $photoUrl, $html);
        $html = str_replace('{{page.photoUrl}}', $photoUrl, $html);
        $html = str_replace('{{page.Title}}', $page->title, $html);
        $html = str_replace('{{page.title}}', $page->title, $html);
        $html = str_replace('{{page.LastModifiedDate}}', $lastModifiedDate, $html);
        $html = str_replace('{{page.lastModifiedDate}}', $lastModifiedDate, $html);
        $html = str_replace('{{page.LastModifiedBy}}', $name, $html);
        $html = str_replace('{{page.lastModifiedBy}}', $name, $html);

        // replace timestamp
        $html = str_replace('{{timestamp}}', time(), $html);

        // replace year
        $html = str_replace('{{year}}', date('Y'), $html);

        // set images URL
        $imagesUrl = 'files/';

        // set iconURL
        $iconUrl = '';

        if ($site->icon != '') {
            $iconUrl = 'files/' . $site->icon;
        }

        // replace
        $html = str_replace('ng-src', 'src', $html);
        $html = str_replace('{{site.ImagesUrl}}', $imagesUrl, $html);
        $html = str_replace('{{site.imagesUrl}}', $imagesUrl, $html);
        $html = str_replace('{{site.IconUrl}}', $iconUrl, $html);
        $html = str_replace('{{site.iconUrl}}', $iconUrl, $html);

        // set fullLogo
        $html = str_replace('{{site.LogoUrl}}', 'files/' . $site->logo, $html);
        $html = str_replace('{{site.logoUrl}}', 'files/' . $site->logo, $html);

        // set altLogo (defaults to full logo if not available)
        if ($site->altLogo != '' && $site->altLogo != NULL) {
            $html = str_replace('{{site.AltLogoUrl}}', 'files/' . $site->altLogo, $html);
            $html = str_replace('{{site.altLogoUrl}}', 'files/' . $site->altLogo, $html);
        } else {
            $html = str_replace('{{site.AltLogoUrl}}', 'files/' . $site->logo, $html);
            $html = str_replace('{{site.altLogoUrl}}', 'files/' . $site->logo, $html);
        }

        // set urls
        $relativeURL = $page->url;

        // replace mustaches syntax {{page.description}} {{site.name}}
        $html = str_replace('{{page.Title}}', $page->title, $html);
        $html = str_replace('{{page.title}}', $page->title, $html);
        $html = str_replace('{{page.Name}}', $page->title, $html);
        $html = str_replace('{{page.name}}', $page->title, $html);
        $html = str_replace('{{page.Description}}', $page->description, $html);
        $html = str_replace('{{page.description}}', $page->description, $html);
        $html = str_replace('{{page.Keywords}}', $page->keywords, $html);
        $html = str_replace('{{page.keywords}}', $page->keywords, $html);
        $html = str_replace('{{page.Callout}}', $page->callout, $html);
        $html = str_replace('{{page.callout}}', $page->callout, $html);
        $html = str_replace('{{site.Name}}', $site->name, $html);
        $html = str_replace('{{site.name}}', $site->name, $html);
        $html = str_replace('{{site.Language}}', $site->language, $html);
        $html = str_replace('{{site.language}}', $site->language, $html);
        $html = str_replace('{{site.Direction}}', $site->direction, $html);
        $html = str_replace('{{site.direction}}', $site->direction, $html);
        $html = str_replace('{{site.IconBg}}', $site->color, $html);
        $html = str_replace('{{site.iconBg}}', $site->color, $html);

        // urls
        $html = str_replace('{{page.Url}}', $relativeURL, $html);
        $html = str_replace('{{page.url}}', $relativeURL, $html);

        return $html;

    }

}