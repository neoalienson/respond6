System.register(['angular2/core', '/app/shared/services/site.service'], function(exports_1, context_1) {
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
    var core_1, site_service_1;
    var CreateComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (site_service_1_1) {
                site_service_1 = site_service_1_1;
            }],
        execute: function() {
            CreateComponent = (function () {
                function CreateComponent(_siteService) {
                    this._siteService = _siteService;
                }
                /**
                 * Init pages
                 */
                CreateComponent.prototype.ngOnInit = function () {
                    this.model = {
                        name: '',
                        theme: '',
                        email: '',
                        password: '',
                        passcode: ''
                    };
                };
                /**
                 * Create the site
                 *
                 */
                CreateComponent.prototype.submit = function () {
                    var _this = this;
                    this._siteService.create(this.model.name, this.model.theme, this.model.email, this.model.password, this.model.passcode)
                        .subscribe(function (data) { _this.site = data; _this.success(); }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Handles a successful create
                 *
                 */
                CreateComponent.prototype.success = function () {
                    console.log(this.site);
                    alert('success! site=' + this.site.id);
                    // clear model
                    this.model = {
                        name: '',
                        theme: '',
                        email: '',
                        password: '',
                        passcode: ''
                    };
                };
                CreateComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-create',
                        templateUrl: './app/create/create.component.html',
                        providers: [site_service_1.SiteService]
                    }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof site_service_1.SiteService !== 'undefined' && site_service_1.SiteService) === 'function' && _a) || Object])
                ], CreateComponent);
                return CreateComponent;
                var _a;
            }());
            exports_1("CreateComponent", CreateComponent);
        }
    }
});
//# sourceMappingURL=create.component.js.map