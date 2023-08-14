import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductVariantCreateComponent} from './product-variant-create/product-variant-create.component';
import {ProductVariantEditComponent} from './product-variant-edit/product-variant-edit.component';


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
    ProductVariantCreateComponent, ProductVariantEditComponent
  ],
  entryComponents: [
    ProductVariantCreateComponent, ProductVariantEditComponent
  ],
  exports: [
    ProductVariantCreateComponent, ProductVariantEditComponent
  ]
})
export class ProductVariantModalModule {
}
