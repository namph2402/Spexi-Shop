import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ProductCategoryCreateComponent} from './product-category-create/product-category-create.component';
import {ProductCategoryEditComponent} from './product-category-edit/product-category-edit.component';

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
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    TabsModule.forRoot(),
  ],
  declarations: [
    ProductCategoryCreateComponent, ProductCategoryEditComponent
  ],
  entryComponents: [
    ProductCategoryCreateComponent, ProductCategoryEditComponent
  ],
  exports: [
    ProductCategoryCreateComponent, ProductCategoryEditComponent
  ]
})
export class ProductCategoryModalModule {
}
