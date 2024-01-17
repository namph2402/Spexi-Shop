import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ShippingFeeEditComponent} from './shipping-fee-edit/shipping-fee-edit.component';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ShippingFeeImportComponent} from './shipping-fee-import/shipping-fee-import.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
  ],
  declarations: [
    ShippingFeeEditComponent, ShippingFeeImportComponent
  ],
  entryComponents: [
    ShippingFeeEditComponent, ShippingFeeImportComponent
  ],
  exports: [
    ShippingFeeEditComponent, ShippingFeeImportComponent
  ]
})
export class ShippingFeeModalModule {
}
