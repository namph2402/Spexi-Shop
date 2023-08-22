import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductWarehouseListComponent} from './product-warehouse-list/product-warehouse-list.component';
import {ProductWarehouseModalModule} from './product-warehouse.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    UiSwitchModule,
    AngularMultiSelectModule,
    CKEditorModule,
    ProductWarehouseModalModule
  ],
  declarations: [ProductWarehouseListComponent],
  entryComponents: [ProductWarehouseListComponent],
  exports: [ProductWarehouseListComponent]
})
export class ProductWarehouseModule {
}
