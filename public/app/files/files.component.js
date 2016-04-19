System.register(['angular2/core', 'angular2-jwt/angular2-jwt', 'angular2/router', '/app/shared/services/file.service', '/app/shared/components/files/remove-file/remove-file.component', '/app/shared/components/drawer/drawer.component'], function(exports_1, context_1) {
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
    var core_1, angular2_jwt_1, router_1, file_service_1, remove_file_component_1, drawer_component_1;
    var FilesComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (angular2_jwt_1_1) {
                angular2_jwt_1 = angular2_jwt_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (file_service_1_1) {
                file_service_1 = file_service_1_1;
            },
            function (remove_file_component_1_1) {
                remove_file_component_1 = remove_file_component_1_1;
            },
            function (drawer_component_1_1) {
                drawer_component_1 = drawer_component_1_1;
            }],
        execute: function() {
            FilesComponent = (function () {
                function FilesComponent(_fileService) {
                    this._fileService = _fileService;
                }
                /**
                 * Init files
                 *
                 */
                FilesComponent.prototype.ngOnInit = function () {
                    this.id = localStorage.getItem('respond.siteId');
                    this.removeVisible = false;
                    this.drawerVisible = false;
                    this.file = {};
                    this.list();
                };
                /**
                 * Updates the list
                 */
                FilesComponent.prototype.list = function () {
                    var _this = this;
                    this.reset();
                    this._fileService.list()
                        .subscribe(function (data) { _this.files = data; }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Resets an modal booleans
                 */
                FilesComponent.prototype.reset = function () {
                    this.removeVisible = false;
                    this.drawerVisible = false;
                    this.file = {};
                };
                /**
                 * Sets the list item to active
                 *
                 * @param {File} file
                 */
                FilesComponent.prototype.setActive = function (file) {
                    this.selectedFile = file;
                };
                /**
                 * Shows the drawer
                 */
                FilesComponent.prototype.toggleDrawer = function () {
                    this.drawerVisible = !this.drawerVisible;
                };
                /**
                 * Shows the remove dialog
                 *
                 * @param {File} file
                 */
                FilesComponent.prototype.showRemove = function (file) {
                    this.removeVisible = true;
                    this.file = file;
                };
                FilesComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-files',
                        templateUrl: './app/files/files.component.html',
                        providers: [file_service_1.FileService],
                        directives: [remove_file_component_1.RemoveFileComponent, drawer_component_1.DrawerComponent],
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof file_service_1.FileService !== 'undefined' && file_service_1.FileService) === 'function' && _a) || Object])
                ], FilesComponent);
                return FilesComponent;
                var _a;
            }());
            exports_1("FilesComponent", FilesComponent);
        }
    }
});
//# sourceMappingURL=files.component.js.map