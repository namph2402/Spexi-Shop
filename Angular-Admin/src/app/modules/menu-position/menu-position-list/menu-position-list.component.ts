import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {FieldForm, ModalResult} from '../../../core/common';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {MenuPositionMeta} from '../menu-position.meta';
import {MenuPositionCreateComponent} from '../menu-position-create/menu-position-create.component';
import {MenuPositionEditComponent} from '../menu-position-edit/menu-position-edit.component';
import {MenuPositionService} from '../menu-position.service';
import {ObjectUtil} from '../../../core/utils';
import {MenuMeta} from '../../menu/menu.meta';
import {MenuCreateComponent} from '../../menu/menu-create/menu-create.component';
import {MenuService} from '../../menu/menu.service';
import {MenuEditComponent} from '../../menu/menu-edit/menu-edit.component';

@Component({
  selector: 'app-menu-position',
  templateUrl: './menu-position-list.component.html',
  styleUrls: ['./menu-position-list.component.css'],
  providers: [MenuPositionService, MenuService]
})
export class MenuPositionListComponent extends AbstractCRUDComponent<MenuPositionMeta> {

  constructor(
    service: MenuPositionService,
    modal: BsModalService,
    builder: FormBuilder,
    private menuService: MenuService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý menu';
  }

  getCreateModalComponent(): any {
    return MenuPositionCreateComponent;
  }

  getEditModalComponent(): any {
    return MenuPositionEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
    ];
  }

  initNewModel(): MenuPositionMeta {
    return new MenuPositionMeta();
  }

  createGroup() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<MenuPositionMeta> = <AbstractModalComponent<MenuPositionMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<MenuPositionMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editGroup(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<MenuPositionMeta> = <AbstractModalComponent<MenuPositionMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<MenuPositionMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  createMenu(item: MenuPositionMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, MenuCreateComponent);
    const modalRef = this.modalService.show(MenuCreateComponent, config);
    const modal: AbstractModalComponent<MenuMeta> = <AbstractModalComponent<MenuMeta>>modalRef.content;
    const menuMeta = new MenuMeta();
    menuMeta.group_id = item.id;
    modal.setModel(menuMeta);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editMenu(item: MenuMeta) {
    const modalRef = this.modalService.show(MenuEditComponent, {ignoreBackdropClick: true});
    const modal: AbstractModalComponent<MenuMeta> = <AbstractModalComponent<MenuMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<MenuMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  removeMenu(item: MenuMeta) {
    this.menuService.destroy(item['id']).subscribe(() => {
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }

  upOrder(item: MenuMeta) {
    this.menuService.up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: MenuMeta) {
    this.menuService.down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

}
