import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {ShippingUnitCreateComponent} from './shipping-unit-create/shipping-unit-create.component';
import {ShippingUnitEditComponent} from './shipping-unit-edit/shipping-unit-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ShippingUnitPartnerComponent} from './shipping-unit-partner/shipping-unit-partner.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [
    ShippingUnitCreateComponent, ShippingUnitEditComponent, ShippingUnitPartnerComponent
  ],
  entryComponents: [
    ShippingUnitCreateComponent, ShippingUnitEditComponent, ShippingUnitPartnerComponent
  ],
  exports: [
    ShippingUnitCreateComponent, ShippingUnitEditComponent, ShippingUnitPartnerComponent
  ]
})
export class ShippingUnitModalModule {
}
