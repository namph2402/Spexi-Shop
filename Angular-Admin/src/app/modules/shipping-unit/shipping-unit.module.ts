import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ShippingUnitListComponent} from './shipping-unit-list/shipping-unit-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ShippingUnitModalModule} from './shipping-unit.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ShippingUnitListComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routing),
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    ShippingUnitModalModule
  ],
  declarations: [
    ShippingUnitListComponent
  ],
  entryComponents: [],
  exports: []
})
export class ShippingUnitModule {
}
