import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {FormFieldService} from '/app/shared/services/form-field.service';

@Component({
    selector: 'respond-remove-form-field',
    templateUrl: './app/shared/components/forms/remove-form-field/remove-form-field.component.html',
    providers: [FormFieldService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveFormFieldComponent {

  routes;
  errorMessage;

  // model to store
  model;

  _visible: boolean = false;

  // visible input
  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  get visible() { return this._visible; }

  // field input
  @Input()
  set field(field){

    // set visible
    this.model = field;

  }

  // form input
  @Input() form;

  // index
  @Input() index

  // outputs
  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _formFieldService: FormFieldService) {}

  /**
   * Init
   */
  ngOnInit() {

    this.model = {
      label: '',
      type: ''
    };

  }

  /**
   * Hides the modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }

  /**
   * Submits the form
   */
  submit() {

    this._formFieldService.remove(this.form.id, this.index)
                     .subscribe(
                       data => { this.success(); },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * Handles a successful submission
   */
  success() {

    this._visible = false;
    this.onUpdate.emit(null);

  }


}