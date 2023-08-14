import {FormBuilder, FormGroup} from '@angular/forms';
import {EventEmitter, OnDestroy, OnInit} from '@angular/core';
import {AbstractCRUDService} from './crud.service.class';
import {ObjectUtil} from '../utils';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm, ModalResult} from '../common';
import {ArrayUtil} from '../utils/array';

export abstract class AbstractModalComponent<T> implements OnInit, OnDestroy {

  public model: T;
  public onHidden: EventEmitter<ModalResult<T>> = new EventEmitter();
  public formGroup: FormGroup;

  public fields: FieldForm[];
  counter: number = 0;

  protected constructor(public service: AbstractCRUDService<T>, public modal: BsModalRef, public formBuilder: FormBuilder, ...anotherServices: any[]) {
    this.formGroup = this.buildForm();
    this.fields = this.initFieldForm();
    this.onFormChanged();
  }

  public initFieldForm(): FieldForm[] {
    return [];
  }

  public changeEndPointAPI(endpoint: string): void {
    this.service.setURLRestAPI(endpoint);
  }

  public appendEndPointAPI(append: string): void {
    this.service.setURLRestAPI(`${this.service.urlRestAPI}/${append}`);
  }

  public abstract onInit(): void;

  public abstract onDestroy(): void;

  public abstract buildForm(): FormGroup;

  public abstract loaded(): void;

  public onFormChanged(): void {
    this.fields.forEach(v => {
      if (v.type == 'select2') {
        if (v.typeof == 'single') {
          this.onSingleSelectChanged(v.formControl);
        } else if (v.typeof == 'multi') {
          this.onMultiSelectChanged(v.formControl);
        }
      }
    });
  }

  public onSelectChanged(name: string, next?: (value: T) => void, error?: (error: any) => void, complete?: () => void) {
    this.formGroup.controls[name].valueChanges.subscribe(value => {
      if (ArrayUtil.isNotEmpty(value)) {
        this.formGroup.controls[name + '_id'].setValue(value[0].id);
      }
      if (next) {
        next(value);
      }
    }, error, complete);
  }

  public onSingleSelectChanged(name: string, next?: (value: T) => void, error?: (error: any) => void, complete?: () => void) {
    this.formGroup.controls[name].valueChanges.subscribe(value => {
      if (ArrayUtil.isNotEmpty(value)) {
        this.formGroup.controls[name + '_id'].setValue(value[0].id);
      }
      if (next) {
        next(value);
      }
    }, error, complete);
  }

  public onMultiSelectChanged(name: string, next?: (value: T) => void, error?: (error: any) => void, complete?: () => void) {
    this.formGroup.controls[name].valueChanges.subscribe(value => {
      if (ArrayUtil.isNotEmpty(value)) {
        this.formGroup.controls[name + '_ids'].setValue(value.map(e => e.id).join(','));
      }
      if (next) {
        next(value);
      }
    }, error, complete);
  }

  public setFormValue() {
    this.fields.forEach(val => {
      if (this.model[val.formControl] != null) {
        if (val.type == 'select2') {
          this.formGroup.get(val.formControl).setValue([this.model[val.formControl]]);
        } else {
          this.formGroup.get(val.formControl).setValue(this.model[val.formControl]);
        }

      }
    });
    let existsFields = this.fields.map(v => v.formControl);
    Object.keys(this.formGroup.controls).forEach(key => {
      if (existsFields.indexOf(key) < 0) {
        this.formGroup.get(key).setValue(this.model[key]);
      }
    });
  }

  prepareParams() {
    return ObjectUtil.combineValue(this.model, this.formGroup.value);
  }

  create() {
    let item: T = this.prepareParams();
    this.service.store(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  edit() {
    let item: T = this.prepareParams();
    this.service.update(item).subscribe(res => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.combineValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }

  createWithImage() {
    let item: T = this.prepareParams();
    this.service.storeWithImage(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  editWithImage() {
    let item: T = this.prepareParams();
    this.service.updateWithImage(item).subscribe(res => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.combineValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }

  ngOnInit() {
    this.onInit();
  }

  ngOnDestroy() {
    this.onDestroy();
  }

  public setModel(model: T) {
    this.model = model;
    this.loaded();
    let totalLoader = this.fields.filter(field => field.loader).length;
    if (totalLoader == 0) {
      this.setFormValue();
    } else {
      this.loadDynamicDataForSelector();
    }
  }

  updateResourceLoaded() {
    this.counter += 1;
    if (this.counter == this.fields.filter(field => field.loader).length) {
      this.setFormValue();
    }
  }


  public dismiss() {
    this.modal.hide();
    this.onHidden.emit(new ModalResult<T>());
  }

  public close(model: any) {
    this.modal.hide();
    this.onHidden.emit(new ModalResult<any>(model));
  }

  setParamForSelectorLoader(index: number, param: any) {
    this.fields[index].loader.params = param;
  }


  loadDynamicDataForSelector() {
    this.fields.filter(field => field.loader).forEach(field => {
      this[field.loader.functionName](field.loader.params).subscribe(arr => {
        field.data = arr;
        this.updateResourceLoaded();
      });
    });
  }

  getFormValue(key: string) {
    if (!this.formGroup.contains(key)) {
      throw new Error('Không có form control có tên như vậy');
    }
    let form = this.fields.find(value => value.formControl == key);
    if (!form) {
      throw new Error('Không có form control có tên như vậy');
    }
    return this.formGroup.get(key).value;
  }

  onFileUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  setFormValueByKey(key: string, value: any, option?: any) {
    if (!this.formGroup.contains(key)) {
      throw new Error('Không có form control có tên như vậy');
    }
    let form = this.fields.find(value => value.formControl == key);
    if (!form) {
      throw new Error('Không có form control có tên như vậy');
    }
    this.formGroup.controls[key].setValue(value, option);
  }


}

