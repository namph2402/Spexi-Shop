import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {PaymentMethodModalModule} from './payment-method.modal.module';
import {PaymentMethodListComponent} from './payment-method-list/payment-method-list.component';
import { UiSwitchModule } from 'ngx-toggle-switch';

const routing: Routes = [
  {
    path: '',
    component: PaymentMethodListComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
    ModalModule.forRoot(),
    NgSelectModule,
    AngularMultiSelectModule,
    CKEditorModule,
    UiSwitchModule,
    PaymentMethodModalModule
  ],
  declarations: [PaymentMethodListComponent],
  entryComponents: []
})
export class PaymentMethodModule {
}
