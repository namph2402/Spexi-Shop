import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {OrderShipCreateComponent} from './order-ship-create/order-ship-create.component';
import {OrderShipCreateResultComponent} from './order-ship-create-result/order-ship-create-result.component';
import { OrderShipItemComponent } from './order-ship-item/order-ship-item.component';
import { OrderShipInfoComponent } from './order-ship-info/order-ship-info.component';
import { OrderShipNoteComponent } from './order-ship-note/order-ship-note.component';

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
    TabsModule.forRoot(),
    AngularMultiSelectModule,
  ],
  declarations: [
    OrderShipCreateComponent,
    OrderShipCreateResultComponent,
    OrderShipItemComponent,
    OrderShipInfoComponent,
    OrderShipNoteComponent
  ],
  entryComponents: [
    OrderShipCreateComponent,
    OrderShipCreateResultComponent,
    OrderShipItemComponent,
    OrderShipInfoComponent,
    OrderShipNoteComponent
  ],
  exports: [
    OrderShipCreateComponent,
    OrderShipCreateResultComponent,
    OrderShipItemComponent,
    OrderShipInfoComponent,
    OrderShipNoteComponent
  ]
})
export class OrderShipModalModule {
}
