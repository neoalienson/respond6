System.register(['angular2/core', 'angular2/http'], function(exports_1, context_1) {
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
    var core_1, http_1, http_2;
    var UserService;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
                http_2 = http_1_1;
            }],
        execute: function() {
            UserService = (function () {
                function UserService(http) {
                    this.http = http;
                    this._loginUrl = 'api/user/login';
                    this._forgotUrl = 'api/user/forgot';
                    this._resetUrl = 'api/user/reset';
                }
                /**
                 * Login to the application
                 *
                 * @param {string} id The site id
                 * @param {string} email The user's login email
                 * @param {string} password The user's login password
                 * @return {Observable}
                 */
                UserService.prototype.login = function (id, email, password) {
                    var body = JSON.stringify({ id: id, email: email, password: password });
                    var headers = new http_2.Headers({ 'Content-Type': 'application/json' });
                    var options = new http_2.RequestOptions({ headers: headers });
                    return this.http.post(this._loginUrl, body, options)
                        .map(function (res) { return res.json(); });
                };
                /**
                 * Requests the user's password to be reset
                 *
                 * @param {string} id The site id
                 * @param {string} email The user's login email
                 * @return {Observable}
                 */
                UserService.prototype.forgot = function (id, email) {
                    var body = JSON.stringify({ id: id, email: email });
                    var headers = new http_2.Headers({ 'Content-Type': 'application/json' });
                    var options = new http_2.RequestOptions({ headers: headers });
                    return this.http.post(this._forgotUrl, body, options);
                };
                /**
                 * Resets the password
                 *
                 * @param {string} id The site id
                 * @param {string} token The token needed to reset the password
                 * @param {string} password The new password
                 * @return {Observable}
                 */
                UserService.prototype.reset = function (id, token, password) {
                    var body = JSON.stringify({ id: id, token: token, password: password });
                    var headers = new http_2.Headers({ 'Content-Type': 'application/json' });
                    var options = new http_2.RequestOptions({ headers: headers });
                    return this.http.post(this._resetUrl, body, options);
                };
                UserService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http])
                ], UserService);
                return UserService;
            }());
            exports_1("UserService", UserService);
        }
    }
});
//# sourceMappingURL=user.service.js.map