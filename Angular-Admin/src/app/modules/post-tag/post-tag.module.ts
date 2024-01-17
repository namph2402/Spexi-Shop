import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostTagListComponent} from './post-tag-list/post-tag-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {PostTagModalModule} from './post-tag.modal.module';
import {UiSwitchModule} from 'ngx-toggle-switch';

const routing: Routes = [
  {
    path: '',
    component: PostTagListComponent
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
    UiSwitchModule,
    PostTagModalModule
  ],
  declarations: [
    PostTagListComponent,
  ],
  entryComponents: []
})
export class PostTagModule {
}
