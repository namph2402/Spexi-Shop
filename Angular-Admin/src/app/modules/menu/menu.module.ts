import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MenuEditComponent} from './menu-edit/menu-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MenuCreateComponent} from './menu-create/menu-create.component';
import {CKEditorModule} from 'ng2-ckeditor';

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
    MenuEditComponent,
    MenuCreateComponent
  ],
  entryComponents: [
    MenuEditComponent,
    MenuCreateComponent
  ],
  exports: [
    MenuEditComponent,
    MenuCreateComponent
  ]
})
export class MenuModule {
}
