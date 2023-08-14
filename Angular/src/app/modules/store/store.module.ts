import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StoreListComponent} from './store-list/store-list.component';
import {StoreCreateComponent} from './store-create/store-create.component';
import {StoreEditComponent} from './store-edit/store-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

const routing: Routes = [
  {
    path: '',
    component: StoreListComponent
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
    AngularMultiSelectModule,
  ],
  declarations: [StoreListComponent, StoreCreateComponent, StoreEditComponent],
  entryComponents: [StoreCreateComponent, StoreEditComponent]
})
export class StoreModule {
}
