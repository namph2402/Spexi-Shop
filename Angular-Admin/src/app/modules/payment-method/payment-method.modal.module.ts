import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PaymentMethodCreateComponent} from './payment-method-create/payment-method-create.component';
import {PaymentMethodEditComponent} from './payment-method-edit/payment-method-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';

import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import { PaymentMethodEditVnpayComponent } from './payment-method-vnpay-edit/payment-method-vnpay-edit.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [PaymentMethodCreateComponent, PaymentMethodEditComponent, PaymentMethodEditVnpayComponent],
  entryComponents: [PaymentMethodCreateComponent, PaymentMethodEditComponent, PaymentMethodEditVnpayComponent],
  exports: [PaymentMethodCreateComponent, PaymentMethodEditComponent, PaymentMethodEditVnpayComponent],
})
export class PaymentMethodModalModule {
}
