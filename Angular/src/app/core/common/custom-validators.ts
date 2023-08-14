import {AbstractControl} from '@angular/forms';

export class CustomValidators {

  static validateUrl(control: AbstractControl) {
    if (control.value) {
      if (control.value.startsWith('http://') || control.value.startsWith('https://')) {
        return null;
      }
    }
    return {invalidUrl: true};
  }

  static validateArray(control: AbstractControl) {
    const value: any[] = control.value;
    if (value && value.length) {
      return null;
    }
    return {invalid: true};
  }

  static validateIdNumber(control: AbstractControl) {
    const value: string = control.value;
    if (/^\d{9}$/.test(value)) {
      return null;
    }
    if (/^\d{12}$/.test(value)) {
      return null;
    }
    return {invalid: true};
  }

  static validatePhone(control: AbstractControl) {
    const value: string = control.value;
    if (/^\d{10}$/.test(value) && value.startsWith('0')) {
      return null;
    }
    return {invalid: true};
  }

  static validateTaxCode(control: AbstractControl) {
    const value: string = control.value;
    if (/^\d{10}$/.test(value)) {
      return null;
    }
    if (/\d{10}\-\d{3}$/.test(value)) {
      return null;
    }
    return {invalid: true};
  }

}
