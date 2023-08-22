import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StorePostListComponent} from './store-post-list/store-post-list.component';
import {StorePostCreateComponent} from './store-post-create/store-post-create.component';
import {StorePostEditComponent} from './store-post-edit/store-post-edit.component';
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
    component: StorePostListComponent
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
  declarations: [StorePostListComponent, StorePostCreateComponent, StorePostEditComponent],
  entryComponents: [StorePostCreateComponent, StorePostEditComponent]
})
export class StorePostModule {
}
