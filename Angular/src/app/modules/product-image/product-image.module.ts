import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductImageModalModule} from './product-image.modal.module';
import {ProductImageListComponent} from './product-image-list/product-image-list.component';

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
    ProductImageModalModule
  ],
  declarations: [ProductImageListComponent],
  entryComponents: [ProductImageListComponent],
  exports: [ProductImageListComponent]
})
export class ProductImageModule {
}
