import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostTagCreateComponent} from './post-tag-create/post-tag-create.component';
import {PostTagEditComponent} from './post-tag-edit/post-tag-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {PostTagItemCreateComponent} from './post-tag-item-create/post-tag-item-create.component';
import {PostTagItemListComponent} from './post-tag-item-list/post-tag-item-list.component';

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
    UiSwitchModule,
    AngularMultiSelectModule,
    CKEditorModule,
  ],
  declarations: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagItemListComponent,
    PostTagItemCreateComponent
  ],
  entryComponents: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagItemListComponent,
    PostTagItemCreateComponent
  ],
  exports: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagItemListComponent,
    PostTagItemCreateComponent
  ]
})
export class PostTagModalModule {
}
