<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class EditController extends Controller
{

    /**
     * Edits a page provided by the querystring, in format ?q=site-name/dir/page.html
     *
     * @return Response
     */
    public function edit(Request $request)
    {

        $q = $request->input('q');

        if($q != NULL){

          $arr = explode('/', $q);

          if(sizeof($arr) > 0) {

            $site = $arr[0];

            // load page
            $path = rtrim(app()->basePath('public/sites/'.$q), '/');

            if(file_exists($path)) {

              $html = file_get_contents($path);

              // open document
              $doc = \phpQuery::newDocument($html);

              // set base
              $doc['base']->attr('href', '/sites/'.$site.'/');
              $doc['body']->attr('hashedit-url', $q);

              // get defaults
              $sortable = '.col, .column';
              $editable = ['#content'];

              // TODO try to load public/sites/site-name/hashedit-config.json

              // setup sortable
              $doc['body']->attr('hashedit-sortable', $sortable);

              // setup login
              $doc['body']->attr('hashedit-login', '/login/'.$site);

              // setup auth
              $doc['body']->attr('hashedit-auth', 'token');
              $doc['body']->attr('hashedit-auth-header', 'X-AUTH');

              // add development (for now)
              $doc['body']->attr('hashedit-dev', '');

              // setup editable area
              foreach($editable as $value){
                $doc[$value]->attr('hashedit', '');
                $doc[$value]->attr('hashedit-selector', $value);
              }

              // setup references
              $els = $doc['body *'];
              $i = 1;

              // add references to each element
              foreach($els as $el) {
                pq($el)->attr('data-ref', $i);
                $i++;
              }

              // setup hashedit
              $hashedit = '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">'.
                          '<script src="/hashedit/js/fetch.min.js"></script>'.
                          '<script src="/node_modules/dropzone/dist/min/dropzone.min.js"></script>'.
                          '<link type="text/css" href="/node_modules/dropzone/dist/min/dropzone.min.css" rel="stylesheet">'.
                          '<script src="/node_modules/sortablejs/Sortable.min.js"></script>'.
                          '<script src="/dev/hashedit/js/hashedit.js"></script>'.
                          '<script>hashedit.setup();</script>';

              // load the edit library
              $doc['body']->append($hashedit);

              // get updated html
              $contents = $doc->htmlOuter();

              return $contents;

            }


          }


        }

    }

    /**
     * Retrieves a mirror for hte html
     *
     * @return Response
     */
    public function mirror(Request $request)
    {

        $q = $request->input('q');

        if($q != NULL){

          $arr = explode('/', $q);

          if(sizeof($arr) > 0) {

            $site = $arr[0];

            // load page
            $path = rtrim(app()->basePath('public/sites/'.$q), '/');

            if(file_exists($path)) {

              $html = file_get_contents($path);

              // open document
              $doc = \phpQuery::newDocument($html);

              // setup references
              $els = $doc['body *'];
              $i = 1;

              // add references to each element
              foreach($els as $el) {
                pq($el)->attr('data-ref', $i);
                $i++;
              }

              // setup editable area
              $editable = ['#content'];

              foreach($editable as $value){
                $doc[$value]->attr('hashedit', '');
                $doc[$value]->attr('hashedit-selector', $value);
              }

              // remove any scripts that could mess up the DOM
              $els = $doc['script'];

              foreach($els as $el) {
                pq($el)->remove();
              }

              // remove any links that could mess up the dom
              $els = $doc['link'];

              foreach($els as $el) {
                pq($el)->remove();
              }

              // get updated html
              $contents = $doc->htmlOuter();

              return $contents;

            }


          }


        }

    }


}