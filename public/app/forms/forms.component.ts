import {Component} from 'angular2/core';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router';
import {FormService} from '/app/shared/services/form.service';
import {FormFieldService} from '/app/shared/services/form-field.service';
import {AddFormComponent} from '/app/shared/components/forms/add-form/add-form.component';
import {EditFormComponent} from '/app/shared/components/forms/edit-form/edit-form.component';
import {RemoveFormComponent} from '/app/shared/components/forms/remove-form/remove-form.component';
import {AddFormFieldComponent} from '/app/shared/components/forms/add-form-field/add-form-field.component';
import {EditFormFieldComponent} from '/app/shared/components/forms/edit-form-field/edit-form-field.component';
import {RemoveFormFieldComponent} from '/app/shared/components/forms/remove-form-field/remove-form-field.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-forms',
    templateUrl: './app/forms/forms.component.html',
    providers: [FormService, FormFieldService],
    directives: [AddFormComponent, EditFormComponent, RemoveFormComponent, AddFormFieldComponent, EditFormFieldComponent, RemoveFormFieldComponent, DrawerComponent]
})

@CanActivate(() => tokenNotExpired())

export class FormsComponent {

  id;
  forms;
  fields;
  errorMessage;
  selectedForm;
  selectedField;
  selectedIndex;
  addVisible: boolean;
  editVisible: boolean;
  removeVisible: boolean;
  addFieldVisible: boolean;
  editFieldVisible: boolean;
  removeFieldVisible: boolean;
  drawerVisible: boolean;
  overflowVisible: boolean;

  constructor (private _formService: FormService, private _formFieldService: FormFieldService) {}

  /**
   * Init
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.addVisible = false;
    this.editVisible = false;
    this.removeVisible = false;
    this.addFieldVisible = false;
    this.editFieldVisible = false;
    this.removeFieldVisible = false;
    this.drawerVisible = false;
    this.overflowVisible = false;
    this.forms;
    this.fields;

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this.reset();

    this._formService.list()
                     .subscribe(
                       data => { this.forms = data; this.success(); },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * handles the list successfully updated
   */
  success () {

    var x, flag = false;

    // check if selected form is set
    if(this.forms.length > 0 && this.forms != undefined) {

      if(this.selectedForm !== undefined && this.selectedForm !== null) {
        for(x=0; x<this.forms.length; x++) {
          if(this.forms[x].id === this.selectedForm.id) {
            flag = true;
          }
        }
      }

      // check if id is in array
      if(flag === false) {
        this.selectedForm = this.forms[0];
      }

    }

    // update fields
    if(this.selectedForm !== null) {
      this.listFields();
    }

  }

  /**
   * list fields in the form
   */
  listFields() {

    this._formFieldService.list(this.selectedForm.id)
                     .subscribe(
                       data => { this.fields = data; },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * Resets screen
   */
  reset() {
    this.addVisible = false;
    this.editVisible = false;
    this.removeVisible = false;
    this.addFieldVisible = false;
    this.editFieldVisible = false;
    this.removeFieldVisible = false;
    this.drawerVisible = false;
    this.overflowVisible = false;
  }

  /**
   * Sets the form to active
   *
   * @param {Form} form
   */
  setActive(form) {
    this.selectedForm = form;

    this.listFields();
  }

  /**
   * Sets the list field to active
   *
   * @param {FormField} field
   */
  setFieldActive(field) {
    this.selectedField = field;
    this.selectedIndex = this.fields.indexOf(field);
  }

  /**
   * Shows the drawer
   */
  toggleDrawer() {
    this.drawerVisible = !this.drawerVisible;
  }

  /**
   * Shows the overflow menu
   */
  toggleOverflow() {
    this.overflowVisible = !this.overflowVisible;
  }

  /**
   * Shows the add dialog
   */
  showAdd() {
    this.addVisible = true;
  }

  /**
   * Shows the edit dialog
   */
  showEdit() {
    this.editVisible = true;
  }

  /**
   * Shows the remove dialog
   *
   * @param {Form} form
   */
  showRemove() {
    this.removeVisible = true;
  }

  /**
   * Shows the add dialog
   */
  showAddField() {
    this.addFieldVisible = true;
  }

  /**
   * Shows the edit dialog
   */
  showEditField() {
    this.editFieldVisible = true;
  }

  /**
   * Shows the remove dialog
   *
   * @param {FormField} field
   */
  showRemoveField(field) {
    this.removeFieldVisible = true;
  }

  /**
   * Move the field up
   *
   * @param {FormField} field
   */
  moveFieldUp(field) {

    var i = this.fields.indexOf(field);

    if(i != 0) {
      this.fields.splice(i, 1);
      this.fields.splice(i-1, 0, field);
    }

    this.updateOrder();

  }

  /**
   * Move the field down
   *
   * @param {FormField} field
   */
  moveFieldDown(field) {

    var i = this.fields.indexOf(field);

    if(i != (this.fields.length-1)) {
      this.fields.splice(i, 1);
      this.fields.splice(i+1, 0, field);
    }

    this.updateOrder();

  }

  /**
   * Updates the order of the field fields
   *
   */
  updateOrder() {

    this._formFieldService.updateOrder(this.selectedForm.id, this.fields)
                     .subscribe(
                       data => { },
                       error =>  this.errorMessage = <any>error
                      );
  }


}