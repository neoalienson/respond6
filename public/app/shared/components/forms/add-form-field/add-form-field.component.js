System.register(['angular2/core', 'angular2/router', 'angular2-jwt/angular2-jwt', '/app/shared/services/form-field.service'], function(exports_1, context_1) {
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
    var core_1, router_1, angular2_jwt_1, form_field_service_1;
    var AddFormFieldComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (angular2_jwt_1_1) {
                angular2_jwt_1 = angular2_jwt_1_1;
            },
            function (form_field_service_1_1) {
                form_field_service_1 = form_field_service_1_1;
            }],
        execute: function() {
            AddFormFieldComponent = (function () {
                function AddFormFieldComponent(_formFieldService) {
                    this._formFieldService = _formFieldService;
                    // model to store
                    this.model = {
                        label: '',
                        type: '',
                        required: false,
                        options: '',
                        helperText: '',
                        placeholder: '',
                        cssClass: ''
                    };
                    // visible input
                    this._visible = false;
                    // outputs
                    this.onCancel = new core_1.EventEmitter();
                    this.onAdd = new core_1.EventEmitter();
                }
                Object.defineProperty(AddFormFieldComponent.prototype, "visible", {
                    get: function () { return this._visible; },
                    set: function (visible) {
                        // set visible
                        this._visible = visible;
                        // reset model
                        this.model = {
                            label: '',
                            type: '',
                            required: false,
                            options: '',
                            helperText: '',
                            placeholder: '',
                            cssClass: ''
                        };
                    },
                    enumerable: true,
                    configurable: true
                });
                /**
                 * Init
                 */
                AddFormFieldComponent.prototype.ngOnInit = function () {
                };
                /**
                 * Hides the add modal
                 */
                AddFormFieldComponent.prototype.hide = function () {
                    this._visible = false;
                    this.onCancel.emit(null);
                };
                /**
                 * Submits the form
                 */
                AddFormFieldComponent.prototype.submit = function () {
                    var _this = this;
                    this._formFieldService.add(this.form.id, this.model.label, this.model.type, this.model.required, this.model.options, this.model.helperText, this.model.placeholder, this.model.cssClass)
                        .subscribe(function (data) { _this.success(); }, function (error) { _this.errorMessage = error; _this.error(); });
                };
                /**
                 * Handles a successful add
                 */
                AddFormFieldComponent.prototype.success = function () {
                    toast.show('success');
                    this._visible = false;
                    this.onAdd.emit(null);
                };
                /**
                 * Handles an error
                 */
                AddFormFieldComponent.prototype.error = function () {
                    console.log('[respond.error] ' + this.errorMessage);
                    toast.show('failure');
                };
                __decorate([
                    core_1.Input(), 
                    __metadata('design:type', Boolean), 
                    __metadata('design:paramtypes', [Boolean])
                ], AddFormFieldComponent.prototype, "visible", null);
                __decorate([
                    core_1.Input(), 
                    __metadata('design:type', Object)
                ], AddFormFieldComponent.prototype, "form", void 0);
                __decorate([
                    core_1.Output(), 
                    __metadata('design:type', Object)
                ], AddFormFieldComponent.prototype, "onCancel", void 0);
                __decorate([
                    core_1.Output(), 
                    __metadata('design:type', Object)
                ], AddFormFieldComponent.prototype, "onAdd", void 0);
                AddFormFieldComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-add-form-field',
                        templateUrl: './app/shared/components/forms/add-form-field/add-form-field.component.html',
                        providers: [form_field_service_1.FormFieldService]
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof form_field_service_1.FormFieldService !== 'undefined' && form_field_service_1.FormFieldService) === 'function' && _a) || Object])
                ], AddFormFieldComponent);
                return AddFormFieldComponent;
                var _a;
            }());
            exports_1("AddFormFieldComponent", AddFormFieldComponent);
        }
    }
});
//# sourceMappingURL=add-form-field.component.js.map