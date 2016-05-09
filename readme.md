# Respond CMS

Respond 6 is a multi-site, flat-file CMS powered by Angular 2 and Lumen.  Sites built using Respond are static, front-end framework agnostic and use Polymer web components for advanced functionality.

### Status
Version: 6.0.0
Status: Pre-Beta

### Prerequisites
1. npm install -g typings gulp
2. npm install gulp
3. install composer https://getcomposer.org/

### Installation
1. mkdir respond
2. git clone https://github.com/madoublet/respond6 .
3. npm install
4. gulp
5. cp .env.example .env
6. nano .env
7. mkdir public/sites
8. chown -R www-data public/sites
9. mkdir resources/sites
10. chown -R www-data resources/sites
11. composer update

### Design Goals
- Modern application stack: Angular 2 + Laravel
- Static sites powered by Polymer
- No database
- Easy installation, no configuration
- Easy to write plugins
- Sites as themes
- Intuitive editing experience
- Powerful developer features to promote efficiency

### Consumer features
- Fast, easy-to-use app
- Desktop focused, mobile ready
- Intuitive editing experience
- Quick access to common customizations (forms, galleries, etc.)

### Developer features
- Easy to write plugins
- Sites as themes
- Snippets to promote code re-use
- Static sites lower deployment costs
- Easy backups and migrations due to flat-file structure
- Popular frameworks make it easier to build on the CMS


### Developing for Respond

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