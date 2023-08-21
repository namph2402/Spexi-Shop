import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {DashboardService} from './dashboard.service';
import {Chart} from 'chart.js';

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
  percent: any[];
  quantityOrder: any[];

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
      this.percent = val['percents'];
      this.quantityOrder = val['quantity'];

      //Chart
      new Chart("order", {
        type: 'bar',
        data: {
          labels: ['T.1', 'T.2', 'T.3', 'T.4', 'T.5', 'T.6', 'T.7', 'T.8', 'T.9', 'T.10', 'T.11', 'T.12'],
          datasets: [{
            label: 'Đơn hàng bán',
            data: this.quantityOrder,
            backgroundColor: 'rgba(12, 167, 76, 0.5)',
            borderColor: 'rgb(12, 167, 76)',
            borderWidth: 2
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: false
            }
          }
        },
      });

      new Chart("amount", {
        type: 'line',
        data: {
          labels: ['T.1', 'T.2', 'T.3', 'T.4', 'T.5', 'T.6', 'T.7', 'T.8', 'T.9', 'T.10', 'T.11', 'T.12'],
          datasets: [{
            label: 'Doanh số bán hàng',
            data: this.percent,
            backgroundColor: 'rgba(0, 0, 0, 0)',
            borderColor: 'rgb(234, 238, 0)',
            borderWidth: 2
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: false
            }
          }
        },
      });

      new Chart("product", {
        type: 'doughnut',
        data : {
          labels: ['Red Red Red Red', 'Blue Blue Blue Blue', 'Yellow Blue Blue Blue', 'Blues Blue Blue Blue', 'Yellows Blue Blue Blue'],
          datasets: [{
            label: 'My First Dataset',
            data: [300, 50, 100, 32, 40],
            backgroundColor: [
              'rgb(255, 99, 132)',
              'rgb(54, 162, 235)',
              'rgb(202, 99, 132)',
              'rgb(24, 162, 235)',
              'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
          }]
        }
      });

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
