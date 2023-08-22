import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ShippingFeeListComponent} from './shipping-fee-list/shipping-fee-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ShippingFeeModalModule} from './shipping-fee.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ShippingFeeListComponent
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
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
    ShippingFeeModalModule,
  ],
  declarations: [ShippingFeeListComponent],
  entryComponents: [],
  exports: []
})
export class ShippingFeeModule {
}
