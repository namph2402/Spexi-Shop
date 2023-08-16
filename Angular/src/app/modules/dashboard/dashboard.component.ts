import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {DashboardService} from './dashboard.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [DashboardService]
})
export class DashboardComponent extends AbstractCRUDComponent<any> {

  boxes: any[];
  products: any[];
  orders: any[];

  constructor(
    service: DashboardService,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  onDestroy(): void {
  }

  onInit(): void {
    this.load();
  }

  ngOnInit(): void {
    this.service.loadAll().subscribe(val => {
      this.boxes = val['boxes'];
      this.products = val['products'];
      this.orders = val['orders'];
    });
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getTitle(): string {
    return 'Bảng điều khiển';
  }

  initNewModel(): any {
    return undefined;
  }

}
