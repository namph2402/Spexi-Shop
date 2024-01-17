import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ProductTagCreateComponent} from './product-tag-create/product-tag-create.component';
import {ProductTagEditComponent} from './product-tag-edit/product-tag-edit.component';
import {ProductTagItemListComponent} from './product-tag-item-list/product-tag-item-list.component';
import {ProductTagItemCreateComponent} from './product-tag-item-create/product-tag-item-create.component';

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
    ProductTagCreateComponent, ProductTagEditComponent, ProductTagItemListComponent, ProductTagItemCreateComponent
  ],
  entryComponents: [
    ProductTagCreateComponent, ProductTagEditComponent, ProductTagItemListComponent, ProductTagItemCreateComponent
  ],
  exports: [
    ProductTagCreateComponent, ProductTagEditComponent, ProductTagItemListComponent, ProductTagItemCreateComponent
  ]
})
export class ProductTagModalModule {
}
