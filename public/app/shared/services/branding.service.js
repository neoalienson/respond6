System.register(['angular2/core', 'angular2/http', 'angular2-jwt/angular2-jwt'], function(exports_1, context_1) {
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
    var core_1, http_1, angular2_jwt_1, http_2;
    var BrandingService;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
                http_2 = http_1_1;
            },
            function (angular2_jwt_1_1) {
                angular2_jwt_1 = angular2_jwt_1_1;
            }],
        execute: function() {
            BrandingService = (function () {
                function BrandingService(http, authHttp, authConfig) {
                    this.http = http;
                    this.authHttp = authHttp;
                    this.authConfig = authConfig;
                    this._listUrl = 'api/branding/list';
                    this._editUrl = 'api/branding/edit';
                }
                /**
                 * Lists branding items
                 *
                 */
                BrandingService.prototype.list = function (id) {
                    var url = this._listUrl;
                    return this.authHttp.get(url).map(function (res) { return res.json(); });
                };
                /**
                 * Edits a branding item
                 *
                 * @param {array} settings
                 * @return {Observable}
                 */
                BrandingService.prototype.edit = function (settings) {
                    var body = JSON.stringify({ settings: settings });
                    var headers = new http_2.Headers({ 'Content-Type': 'application/json' });
                    var options = new http_2.RequestOptions({ headers: headers });
                    return this.authHttp.post(this._editUrl, body, options);
                };
                BrandingService = __decorate([
                    core_1.Injectable(), 
                    __metadata('design:paramtypes', [http_1.Http, angular2_jwt_1.AuthHttp, angular2_jwt_1.AuthConfig])
                ], BrandingService);
                return BrandingService;
            }());
            exports_1("BrandingService", BrandingService);
        }
    }
});
//# sourceMappingURL=branding.service.js.map