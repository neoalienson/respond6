import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {FormFieldService} from '/app/shared/services/form-field.service';

@Component({
    selector: 'respond-add-form-field',
    templateUrl: './app/shared/components/forms/add-form-field/add-form-field.component.html',
    providers: [FormFieldService]
})

@CanActivate(() => tokenNotExpired())

export class AddFormFieldComponent {

  errorMessage;

  // model to store
  model = {
    label: '',
    type: '',
    required: false,
    options: '',
    helperText: '',
    placeholder: '',
    cssClass: ''
  };

  // visible input
  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

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

  }

  get visible() { return this._visible; }

  // form input
  @Input() form;

  // outputs
  @Output() onCancel = new EventEmitter<any>();
  @Output() onAdd = new EventEmitter<any>();

  constructor (private _formFieldService: FormFieldService) {}

  /**
   * Init
   */
  ngOnInit() {

  }

  /**
   * Hides the add modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }

  /**
   * Submits the form
   */
  submit() {

    this._formFieldService.add(this.form.id, this.model.label, this.model.type, this.model.required, this.model.options, this.model.helperText, this.model.placeholder, this.model.cssClass)
                     .subscribe(
                       data => { this.success(); },
                       error => { this.errorMessage = <any>error; this.error(); }
                      );

  }

  /**
   * Handles a successful add
   */
  success() {

    toast.show('success');

    this._visible = false;
    this.onAdd.emit(null);

  }

  /**
   * Handles an error
   */
  error() {

    console.log('[respond.error] ' + this.errorMessage);
    toast.show('failure');

  }


}