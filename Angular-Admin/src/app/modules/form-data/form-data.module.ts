import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormDataListComponent} from './form-data-list/form-data-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MenuModule} from '../menu/menu.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

const routing: Routes = [
  {
    path: '',
    component: FormDataListComponent
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
    MenuModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [FormDataListComponent],
  entryComponents: []
})
export class FormDataModule {
}
