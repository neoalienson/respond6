# Respond CMS

Respond 6 is a multi-site, flat-file CMS powered by Angular 2 and Lumen.  Sites built using Respond are front-end framework agnostic and use Polymer web components for advanced functionality.

### Status
Version: 6.0.0
Status: Pre-Beta (experienced devs only, not all pieces functional)

### Prerequisitie (note: during development only)
1. npm install -g typings gulp
2. npm install gulp
3. install composer https://getcomposer.org/

### Installation
1. mkdir respond
2. git clone https://github.com/madoublet/respond6 .
3. npm install (note: during development only)
4. gulp (note: during development only)
5. cp .env.example .env
6. update .env
7. mkdir public/sites
8. chown -R www-data public/sites
9. mkdir resources/sites
10. chown -R www-data resources/sites
11. composer update (note: during development only)

### Implementation differences from Respond 5.x
- No Database, YAML used to store site, menu, user data. Site structure (e.g. paths and page types) inferred from site.
- YAML for theme description, combined into one file
- No built-in LESS interpretation for themes (can be added by developer using build tools, e.g. gulp)
- No index.html in theme, all html in layouts/*.html
- No "magic" replacement for <body> in themes, use &lt;respond-content url="{{page.Url}}" render="publish"&gt;&lt;/respond-content&gt;
- The role="main" attribute used to describe the primary content in a document
- Page edits applied directly to html document, fragments used for XHR requests 
- The render=publish applied at site creation, page add.  Thematic elements not re-published on edit
- No render=publish support within editable content

### Notes

##### Install dependency via NPM, copy to public using gulp
npm install
gulp

##### Run the TypeScript compiler
npm start, tsc

### Resources
##### Laravel + Apache
http://saravanan.tomrain.com/installing-laravel-lumen-with-apache-server/

##### PHP password hashing
https://gist.github.com/nikic/3707231

##### Gulp copy for FE dependencies
https://florian.ec/articles/frontend-dependencies-npm/

##### Angular2 Auth with JWT
https://auth0.com/blog/2015/11/10/introducing-angular2-jwt-a-library-for-angular2-authentication/
https://www.npmjs.com/package/angular2-jwt
https://github.com/auth0/angular2-jwt

##### Angular2 - Including external Libraries
https://blog.nraboy.com/2016/01/include-external-javascript-libraries-in-an-angular-2-typescript-project/

##### Tour of Heroes
https://github.com/johnpapa/angular2-tour-of-heroes/tree/master/app

##### Angular2 Styleguides
https://github.com/mgechev/angular2-style-guide#reusable-libraries

##### Angular2 Http
http://www.metaltoad.com/blog/angular-2-http-observables


##### PHP Formatter
http://beta.phpformatter.com/