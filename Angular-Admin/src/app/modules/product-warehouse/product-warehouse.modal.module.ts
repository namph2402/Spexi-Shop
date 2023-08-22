import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductWarehouseCreateComponent} from './product-warehouse-create/product-warehouse-create.component';
import {ProductWarehouseEditComponent} from './product-warehouse-edit/product-warehouse-edit.component';


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
    ProductWarehouseCreateComponent, ProductWarehouseEditComponent
  ],
  entryComponents: [
    ProductWarehouseCreateComponent, ProductWarehouseEditComponent
  ],
  exports: [
    ProductWarehouseCreateComponent, ProductWarehouseEditComponent
  ]
})
export class ProductWarehouseModalModule {
}
