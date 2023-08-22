import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {WarehouseCreateComponent} from './warehouse-create/warehouse-create.component';
import {WarehouseEditComponent} from './warehouse-edit/warehouse-edit.component';
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
  declarations: [WarehouseCreateComponent, WarehouseEditComponent],
  entryComponents: [WarehouseEditComponent, WarehouseCreateComponent],
  exports: [WarehouseEditComponent, WarehouseCreateComponent],
})
export class WarehouseModalModule {
}
