import {Select2Config} from './field-form-configs/select2-config';
import {ObjectUtil} from '../utils';
import {NumberInputConfig} from './field-form-configs/number-input-config';

export class FieldForm extends Object {
  label: string;
  type: string;
  typeof: string;
  formControl: string;
  placeHolder: string;
  data?: any | any[];
  config?: Select2Config | NumberInputConfig | any;
  loader?: {
    functionName: string,
    params: any,
    mapFunc?: (val) => any
  };
  class?: string;

  static createPasswordInput(labelName: string, formControlName: string, placeHolder: string, classes = null): FieldForm {
    return {
      label: labelName,
      type: 'input',
      typeof: 'password',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        maxLength: null,
        minLength: 0,
      }
    };
  }

  static createTextInput(labelName: string, formControlName: string, placeHolder: string, classes = null): FieldForm {
    return {
      label: labelName,
      type: 'input',
      typeof: 'text',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        maxLength: null,
        minLength: 0,
      }
    };
  }

  static createDateTimeInput(labelName: string, formControlName: string, placeHolder: string, classes = null): FieldForm {
    return {
      label: labelName,
      type: 'input',
      typeof: 'datetime-local',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        maxValue: null,
        minValue: '1970-01-01T00:00',
        step: null,
      }
    };
  }

  static createDateInput(labelName: string, formControlName: string, placeHolder: string, classes = null): FieldForm {
    return {
      label: labelName,
      type: 'input',
      typeof: 'date',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        maxValue: null,
        minValue: '1970-01-01',
        step: null,
      }
    };
  }

  static createNumberInput(labelName: string, formControlName: string, placeHolder: string, classes = null, config: Object = {}): FieldForm {
    return {
      label: labelName,
      type: 'input',
      typeof: 'number',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        isCurrency: false,
        maxValue: null,
        minValue: 0,
        step: null,
      }
    };
  }

  static createMultiSelect2(labelName: string, formControlName: string, placeHolder: string,
                            functionName: string | any[], params: Object = {}, config: Select2Config | Object = {}, classes = null): FieldForm {
    if (typeof functionName === 'string') {
      return {
        label: labelName,
        type: 'select2',
        typeof: 'text',
        formControl: formControlName,
        placeHolder: placeHolder,
        loader: {
          functionName: functionName,
          params: params
        },
        data: [],
        config: ObjectUtil.combineValue(Select2Config.createMulti(), config)
      };
    } else {
      if (Array.isArray(functionName)) {
        return {
          label: labelName,
          type: 'select2',
          typeof: 'text',
          formControl: formControlName,
          placeHolder: placeHolder,
          data: functionName,
          config: ObjectUtil.combineValue(Select2Config.createMulti(), config)
        };
      } else {
        throw 'param invalid';
      }
    }
  }

  static createSingleSelect2(labelName: string, formControlName: string, placeHolder: string,
                             functionName: string | any[], params: Object = {}, config: Select2Config | Object = {}, classes = null): FieldForm {
    if (typeof functionName === 'string') {
      return {
        label: labelName,
        type: 'select2',
        typeof: 'text',
        formControl: formControlName,
        placeHolder: placeHolder,
        loader: {
          functionName: functionName,
          params: params
        },
        data: [],
        config: ObjectUtil.combineValue(Select2Config.createSingle(), config)
      };
    } else {
      if (Array.isArray(functionName)) {
        return {
          label: labelName,
          type: 'select2',
          typeof: 'text',
          formControl: formControlName,
          placeHolder: placeHolder,
          data: functionName,
          config: ObjectUtil.combineValue(Select2Config.createSingle(), config)
        };
      } else {
        throw 'param invalid';
      }
    }
  }

  static createSingleSelect3(labelName: string, formControlName: string, placeHolder: string,
                             functionName: string | any[], params: Object = {}, config: Select2Config | Object = {}, classes = null): FieldForm {
    if (typeof functionName === 'string') {
      return {
        label: labelName,
        type: 'select2',
        typeof: 'text',
        formControl: formControlName,
        placeHolder: placeHolder,
        loader: {
          functionName: functionName,
          params: params
        },
        data: [],
        config: ObjectUtil.combineValue(Select2Config.createSingle1(), config)
      };
    } else {
      if (Array.isArray(functionName)) {
        return {
          label: labelName,
          type: 'select2',
          typeof: 'text',
          formControl: formControlName,
          placeHolder: placeHolder,
          data: functionName,
          config: ObjectUtil.combineValue(Select2Config.createSingle1(), config)
        };
      } else {
        throw 'param invalid';
      }
    }
  }

  static createSelect(labelName: string, formControlName: string, placeHolder: string, data: Object[] | string, classes = null,
                      labelKey: string = 'name', primaryKey: string = 'id'): FieldForm {
    if (typeof data === 'string') {
      return {
        label: labelName,
        type: 'select',
        typeof: 'multi',
        formControl: formControlName,
        placeHolder: placeHolder,
        class: classes,
        data: [],
        loader: {
          functionName: data,
          params: {}
        },
        config: {
          labelKey: labelKey,
          primaryKey: primaryKey,
        }
      };
    } else if (Array.isArray(data)) {
      return {
        label: labelName,
        type: 'select',
        typeof: 'text',
        formControl: formControlName,
        placeHolder: placeHolder,
        class: classes,
        data: data,
        config: {
          labelKey: labelKey,
          primaryKey: primaryKey,
        }
      };
    } else {
      throw 'param invalid';
    }
  }

  static createTextArea(labelName: string, formControlName: string, placeHolder: string, rows_count: number = 5, classes: string = null): FieldForm {
    return {
      label: labelName,
      type: 'textarea',
      typeof: 'text',
      formControl: formControlName,
      placeHolder: placeHolder,
      config: {
        rows: rows_count
      },
      class: classes
    };
  }


  static createCheckbox(labelName: string, formControlName: string, placeHolder: string, classes: string = null): FieldForm {
    return {
      label: labelName,
      type: 'checkbox',
      typeof: 'text',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes
    };
  }

  static createFileInput(labelName: string, formControlName: string, placeHolder: string, onFileChange: any, accept: string = null, classes: string = null) {
    return {
      label: labelName,
      type: 'file',
      typeof: 'text',
      formControl: formControlName,
      placeHolder: placeHolder,
      class: classes,
      config: {
        accept: accept,
        onFileChange: onFileChange,
      }
    };
  }

  static createHtmlInput(labelName: string, formControlName: string, config: any = {}) {
    return {
      label: labelName,
      type: 'html',
      typeof: 'text',
      formControl: formControlName,
      placeHolder: '',
      config: config
    };
  }
}
