<?php

namespace App\Respond\Libraries;

use App\Respond\Models\Site;
use App\Respond\Models\User;
use App\Respond\Models\Page;
use App\Respond\Libraries\Utilities;

use Symfony\Component\Yaml\Parser;

class Publish {

  /**
   * Pubishes the theme to the site
   *
   * @param {Site} $site
   */
  public static function PublishTheme($site) {

    // publish theme files
    $src = app()->basePath().'/resources/themes/'.$site->Theme;
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/themes/'.$site->Theme;

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

    // publish CSS
    Publish::PublishThemeCSS($site);

    // compress CSS
    Publish::CompressCSS($site);

    // publish JS
    Publish::PublishThemeJS($site);

    // publish JS
    Publish::PublishThemeFiles($site);

  }

  /**
   * Pubishes the theme files to the site
   *
   * @param {Site} $site
   */
  public static function PublishThemeFiles($site) {

    // publish js files
    $src = app()->basePath().'/public/sites/'.$site->Id.'/themes/'.$site->Theme.'/files';
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/files';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

  }

  /**
   * Pubishes the theme JS to the site
   *
   * @param {Site} $site
   */
  public static function PublishThemeJS($site) {

    // publish js files
    $src = app()->basePath().'/public/sites/'.$site->Id.'/themes/'.$site->Theme.'/js';
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/js';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

  }

  /**
   * Injects site settings the JS to the site
   *
   * @param {Site} $site
   */
	public static function InjectSiteSettings($site){

		// create settings
		$settings = array(
			'SiteId' => $site->Id,
			'Domain' => $site->Domain,
			'API' => env('APP_URL').'/api',
			'ShowCart' => $site->ShowCart,
			'ShowSearch' => $site->ShowSearch
		);

		// settings
		$str_settings = json_encode($settings);

		// get site file
		$file = app()->basePath().'/public/sites/'.$site->Id.'/js/respond.site.js';

		if(file_exists($file)){

			// get contents
			$content = file_get_contents($file);

			$start = 'settings: {';
			$end = '}';

			// remove { }
			$new = str_replace('{', '', $str_settings);
			$new = str_replace('}', '', $new);

			// replace
			$content = preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$new.'$3', $content);

			// publish updates
			file_put_contents($file, $content);

		}

	}

	/**
   * Injects Payment settings
   *
   * @param {Site} $site
   */
	public static function InjectPaymentSettings($site){

	  // paypal
	  $yaml = new Parser();
    $file = app()->basePath().'/resources/sites/'.$id.'/paypal.yaml';

    if(file_exists($file)) {

      $arr = $yaml->parse(file_get_contents($file));

      // settings
  		$str_settings = 'paypal: {'.json_encode($settings, true).'}';

  		// get site file
  		$file = app()->basePath().'/public/sites/'.$site->Id.'/js/respond.site.js';

  		if(file_exists($file)){

  			// get contents
  			$content = file_get_contents($file);

  			$start = 'payment: [';
  			$end = ']';

  			// replace
  			$content = preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$new.'$3', $content);

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
  public static function PublishThemeCSS($site) {

    // publish css files
    $src = app()->basePath().'/public/sites/'.$site->Id.'/themes/'.$site->Theme.'/css';
    $dest = $dir = app()->basePath().'/public/sites/'.$site->Id.'/css';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

  }

  /**
   * Pubishes the menus from the theme
   *
   * @param {Site} $site
   */
  public static function PublishThemeMenus($site) {

    // publish css files
    $src = app()->basePath().'/public/sites/'.$site->Id.'/themes/'.$site->Theme.'/menus';
    $dest = $dir = app()->basePath().'/public/sites/'.$site->Id.'/data/menus';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

  }

  /**
   * Pubishes the css to the site
   *
   * @param {Site} $site
   */
  public static function CompressCSS($site) {

    $dir = app()->basePath().'/public/sites/'.$site->Id.'/css';

    // combine and compress css
    $files = scandir($dir);
    $combined_css = '';

    // walk through files and combine and minify css
    foreach($files as $value) {

      if(is_file("$dir/$value")) {

        $file = "$dir/$value";
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if($ext == 'css') {

          $css = file_get_contents($file);

          // compress css, #ref: http://manas.tungare.name/software/css-compression-in-php/
          $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
          $css = str_replace(': ', ':', $css);
          $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

          // add to combined css
          $combined_css .= $css;

        }

      }

    }

    // create combined css
    file_put_contents(app()->basePath().'/public/sites/'.$site->Id.'/css/respond.min.css', $combined_css);

  }

  /**
   * Pubishes the menus to the site
   *
   * @param {Site} $site
   */
  public static function PublishLocales($site) {

    // publish theme files
    $src = app()->basePath().'/resources/locales';
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/locales';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);
  }

  /**
   * Pubishes components
   *
   * @param {Site} $site
   */
  public static function PublishComponents($site) {
  
    // production
    $dir = app()->basePath().'/node_modules';
  
    if(env('APP_ENV') == 'development') {
      $dir = app()->basePath().'/public/dev';
    }

    // publish components polyfil
    $src = $dir.'/respond-components/bower_components/webcomponentsjs';
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/components/lib';

    // copy the directory
    Utilities::CopyDirectory($src, $dest);

    // paths to build file
    $src = $dir.'/respond-components/respond-build.html';
    $dest = app()->basePath().'/public/sites/'.$site->Id.'/components/respond-build.html';

    // make directory
    $dir = app()->basePath().'/public/sites/'.$site->Id.'/components/';

    if(!file_exists($dir)){
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
  public static function PublishDefaultContent($site, $user) {

    $yaml = new Parser();

    $file = app()->basePath().'/resources/themes/'.$site->Theme.'/theme.yaml';
    $timestamp = gmdate('D M d Y H:i:s O', time());

    if(file_exists($file)) {

       $arr = $yaml->parse(file_get_contents($file));

       foreach($arr['Pages'] as $page) {

        $url = $page['Url'];
        $title = $page['Title'];
        $description = $page['Description'];
        $source = $page['Source'];
        $layout = $page['Layout'];

        // get source
        $source = app()->basePath().'/resources/themes/'.$site->Theme.'/'.$page['Source'];

        if(file_exists($source)) {

          $content = file_get_contents($source);

          // add page
          $data = array(
            'Title' => $title,
            'Description' => $description,
            'Keywords' => '',
            'Callout' => '',
            'Url' => $url,
            'Layout' => $layout,
            'Language' => 'en',
            'LastModifiedBy' => $user->Email,
            'LastModifiedDate' => $timestamp
          );

          // add a page
          Page::Add($data, $site, $user, $content);

        }

       }

     }

  }

  /**
   * Publishes the page
   *
   * @param {Site} $site
   * @param {Page} $page
   * @param {User} $user
   */
  public static function PublishPage($site, $page, $user) {

    $dest = app()->basePath().'/public/sites/'.$site->Id;

    $imageurl = $dest . 'files/';
    $siteurl  = $site->Domain . '/';

    $url  = '';
    $file = $page->Url;

    // set base
    $base = '';

    // explode url by '/'
		$parts = explode('/', $page->Url);

		// set base based on the depth
		if(sizeof($parts) == 1) {
  		$base = '';
		}
		else {
  		$base = str_repeat('../', sizeof($parts)-1);
		}

    // generate default
    $html    = '';
    $content = '';

    // get layout from theme
    $layout = $dest . '/themes/' . $site->Theme . '/layouts/' . $page->Layout . '.html';

    // get fragment html
    if (file_exists($layout)) {

      // get layout html
      $html = file_get_contents($layout);

      // apply mustache syntax to the layout
      $html = Publish::ApplyMustacheSyntax($html, $site, $page, $user);

      // get phpQuery of file
      $doc = \phpQuery::newDocument($html);

      // set show-cart, show-settings, show-languages, show-login
      if ($site->ShowCart == 1) {
        $doc['body']->addClass('show-cart');
      }

      if ($site->ShowSearch == 1) {
        $doc['body']->addClass('show-search');
      }

    }

    if ($doc !== NULL) {

      // generate the [render=publish] components
      $doc = Publish::GenerateRenderAtPublish($doc, $site, $page);

      // get html
      $html = $doc->htmlOuter();

      // applies the mustache syntax
      $html = Publish::ApplyMustacheSyntax($html, $site, $page, $user);

    } else {
      $html = '';
    }

    // update base
    $html = str_replace('<base href="/">', '<base href="' . $base . '">', $html);

    // file location
    $location = $dest.'/'.$file.'.html';

    $dir = dirname($location);

    // make directory
    if(!file_exists($dir)){
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
  public static function GenerateRenderAtPublish($doc, $site, $page) {

    // set images URL
    $imagesUrl = $site->Domain . '/';
    $yaml = new Parser();
    $ext = '.html';

    if(env('FRIENDLY_URLS') === true) {
      $ext = '';
    }

    foreach ($doc['respond-menu[render=publish]'] as $el) {

      $type = pq($el)->attr('type');
      $cssClass = pq($el)->attr('class');

			// get the type
			if($type != NULL) {

				// get yaml for menu
				$file = app()->basePath().'/public/sites/'.$site->Id.'/data/menus/'.$type.'.yaml';

				if(file_exists($file)) {

  				// init menu
  				$menu = '<ul';

  				// set class if applicable
  				if($cssClass != ''){
  					$menu .= ' class="'.$cssClass.'" respond-menu type="'.$type.'">';
  				}
  				else{
  					$menu .= ' respond-menu type="'.$type.'">';
  				}

  				// get items for type
  				$menuItems = $yaml->parse(file_get_contents($file));

			    $i = 0;
			    $parent_flag = false;
			    $new_parent = true;

          // walk through items
  			  foreach($menuItems as $menuItem){

            $name = $menuItem['Name'];
            $cssClass = $menuItem['CssClass'];
            $url = $menuItem['Url'];
            $priority = $menuItem['Priority'];
            $isNested = $menuItem['IsNested'];

            // set active
            if($page->Url == $url) {
              $cssClass .= ' active';
            }

  					// check for new parent
  					if(isset($arr[$i+1])){
  						if($menuItems[$i+1]['IsNested'] == true && $new_parent==true){
  							$parent_flag = true;
  						}
  					}

  					$menu_root = '/';

  					// check for external links
  					if(strpos($url,'http') !== false) {
  					    $menu_root = '';
  					}

  					if($new_parent == true && $parent_flag == true){
  						$menu .= '<li class="dropdown">';
  						$menu .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">'.$menuItem['Name'].' <span class="caret"></span></a>';
  						$menu .= '<ul class="dropdown-menu">';
  						$new_parent = false;
  					}
  					else{
  				    	$menu .= '<li'.$cssClass.'>';
  						$menu .= '<a href="'.$url.$ext.'">'.$menuItem['Name'].'</a>';
  						$menu .= '</li>';
  				    }

  				    // end parent
  				    if(isset($menuItems[$i+1])){
  						if($menuItems[$i+1]['IsNested'] == false && $parent_flag==true){
  							$menu .= '</ul></li>'; // end parent if next item is not nested
  							$parent_flag = false;
  							$new_parent = true;
  						}
  					}
  					else{
  						if($parent_flag == true){
  							$menu .= '</ul></li>'; // end parent if next menu item is null
  							$parent_flag = false;
  							$new_parent = true;
  						}
  					}

  					$i = $i+1;
  				}

  				$menu .= '</ul>';

  				pq($el)->replaceWith($menu);

        }


			}
			/* isset */

		}
		/* foreach */

    // replace content where render is set to publish
    foreach ($doc['respond-content[render=publish]'] as $el) {

      $url = pq($el)->attr('url');

      // get the url
      if (isset($url)) {

        // replace the / with a period
        $url = str_replace('/', '.', $url);
        $url .= '.html';

        // default is blank
        $content_html = '';

        // get location of content html
        $dest = app()->basePath().'/public/sites/'.$site->Id;
        $content_dest = $dest . '/fragments/page/' . $url;

        // get contents
        if (file_exists($content_dest)) {
          $content_html = file_get_contents($content_dest);
        }

        // update images url
        $content_html = str_replace('{{site.ImagesUrl}}', $imagesUrl, $content_html);

        // set outer text
        if ($content_html != '') {
          pq($el)->replaceWith($content_html);
        }
        
    
      }

    }
    /* foreach */
    
    // recursively call generate if needed
    if(sizeof($doc['[render=publish]']) > 0) {
      $doc = Publish::GenerateRenderAtPublish($doc, $site, $page);
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
  public static function ApplyMustacheSyntax($html, $site, $page, $user) {

    // meta data
    $photo = '';
    $name = '';
    $lastModifiedDate = $page->LastModifiedDate;

    // replace last modified
    if ($page->LastModifiedBy != NULL) {

      // set user infomration
      if ($user != NULL) {
        $photoUrl = $site->Domain . '/files/'.$user->Photo;
        $name = $user->Name;
      }

    }

    // set page information
    $html = str_replace('{{page.PhotoUrl}}', $photoUrl, $html);
    $html = str_replace('{{page.Title}}', $page->Title, $html);
    $html = str_replace('{{page.LastModifiedDate}}', $lastModifiedDate, $html);

    // replace timestamp
    $html = str_replace('{{timestamp}}', time(), $html);

    // replace year
    $html = str_replace('{{year}}', date('Y'), $html);

    // set images URL
    $imagesUrl = 'files/';

    // set iconURL
    $iconUrl = '';

    if ($site->Icon != '') {
      $iconUrl = 'files/' . $site->Icon;
    }

    // replace
    $html = str_replace('ng-src', 'src', $html);
    $html = str_replace('{{site.ImagesUrl}}', $imagesUrl, $html);
    $html = str_replace('{{site.IconUrl}}', $iconUrl, $html);

    // set fullLogo
    $html = str_replace('{{site.LogoUrl}}', 'files/' . $site->Logo, $html);

    // set altLogo (defaults to full logo if not available)
    if ($site->AltLogo != '' && $site->AltLogo != NULL) {
      $html = str_replace('{{site.AltLogoUrl}}', 'files/' . $site->AltLogo, $html);
    } 
    else {
      $html = str_replace('{{site.AltLogoUrl}}', 'files/' . $site->Logo, $html);
    }

    // set urls
    $relativeURL = $page->Url;

    $fullURL = $site->Domain . '/' . $relativeURL;

    // replace mustaches syntax {{page.Description}} {{site.Name}}
    $html = str_replace('{{page.Title}}', $page->Title, $html);
    $html = str_replace('{{page.Name}}', $page->Title, $html);
    $html = str_replace('{{page.Description}}', $page->Description, $html);
    $html = str_replace('{{page.Keywords}}', $page->Keywords, $html);
    $html = str_replace('{{page.Callout}}', $page->Callout, $html);
    $html = str_replace('{{site.Name}}', $site->Name, $html);
    $html = str_replace('{{site.Language}}', $site->Language, $html);
    $html = str_replace('{{site.Direction}}', $site->Direction, $html);
    $html = str_replace('{{site.IconBg}}', $site->Color, $html);

    // urls
    $html = str_replace('{{page.Url}}', $relativeURL, $html);
    $html = str_replace('{{page.FullUrl}}', $fullURL, $html);

    return $html;

  }

}