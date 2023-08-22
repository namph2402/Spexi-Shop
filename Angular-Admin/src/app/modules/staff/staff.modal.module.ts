import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {StaffCreateComponent} from './staff-create/staff-create.component';
import {StaffEditComponent} from './staff-edit/staff-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';


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
  declarations: [StaffCreateComponent, StaffEditComponent],
  entryComponents: [StaffEditComponent, StaffCreateComponent],
  exports: [StaffEditComponent, StaffCreateComponent],
})
export class StaffModalModule {
}
