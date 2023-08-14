import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {NotificationListComponent} from './notification-list/notification-list.component';
import {NotificationCreateComponent} from './notification-create/notification-create.component';
import {NotificationEditComponent} from './notification-edit/notification-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {UiSwitchModule} from 'ngx-toggle-switch';

const routing: Routes = [
  {
    path: '',
    component: NotificationListComponent
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
    UiSwitchModule,
    AngularMultiSelectModule,
  ],
  declarations: [NotificationListComponent, NotificationCreateComponent, NotificationEditComponent],
  entryComponents: [NotificationCreateComponent, NotificationEditComponent]
})
export class NotificationModule {
}
