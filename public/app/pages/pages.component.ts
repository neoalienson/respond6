import { Component } from 'angular2/core';
import { tokenNotExpired } from 'angular2-jwt/angular2-jwt';
import { RouteConfig, RouteParams, ROUTER_DIRECTIVES, APP_BASE_HREF, ROUTER_PROVIDERS, CanActivate } from 'angular2/router';

@Component({
    selector: 'respond-pages',
    templateUrl: './app/pages/pages.component.html'
})

@CanActivate(() => tokenNotExpired())

export class PagesComponent { }