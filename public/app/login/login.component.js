System.register(['angular2/core', 'angular2/router', '/app/shared/services/user.service'], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
        var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
        if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
        else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
        return c > 3 && r && Object.defineProperty(target, key, r), r;
    };
    var __metadata = (this && this.__metadata) || function (k, v) {
        if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
    };
    var core_1, router_1, user_service_1;
    var LoginComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (user_service_1_1) {
                user_service_1 = user_service_1_1;
            }],
        execute: function() {
            LoginComponent = (function () {
                function LoginComponent(_userService, _routeParams, _router) {
                    this._userService = _userService;
                    this._routeParams = _routeParams;
                    this._router = _router;
                }
                LoginComponent.prototype.ngOnInit = function () {
                    this.id = this._routeParams.get('id');
                    localStorage.setItem('respond.siteId', this.id);
                };
                /**
                 * Login to the app
                 *
                 * @param {Event} event
                 * @param {string} email The user's login email
                 * @param {string} password The user's login password
                 */
                LoginComponent.prototype.login = function (event, email, password) {
                    var _this = this;
                    event.preventDefault();
                    this._userService.login(this.id, email, password)
                        .subscribe(function (data) { _this.data = data; _this.success(); }, function (error) { _this.failure(); });
                };
                /**
                 * Handles a successful login
                 */
                LoginComponent.prototype.success = function () {
                    toast.show('success');
                    // set token
                    this.setToken(this.data.token);
                    // navigate
                    this._router.navigate(['Pages']);
                };
                /**
                 * Handles a failed login
                 */
                LoginComponent.prototype.failure = function () {
                    toast.show('failure');
                };
                /**
                 * Routes to the forgot password screen
                 */
                LoginComponent.prototype.forgot = function () {
                    this._router.navigate(['Forgot', { id: this.id }]);
                };
                /**
                 * Sets the token in local storage
                 */
                LoginComponent.prototype.setToken = function (token) {
                    localStorage.setItem('id_token', token);
                };
                LoginComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-login',
                        templateUrl: './app/login/login.component.html',
                        providers: [user_service_1.UserService],
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof user_service_1.UserService !== 'undefined' && user_service_1.UserService) === 'function' && _a) || Object, router_1.RouteParams, router_1.Router])
                ], LoginComponent);
                return LoginComponent;
                var _a;
            }());
            exports_1("LoginComponent", LoginComponent);
        }
    }
});
//# sourceMappingURL=login.component.js.map