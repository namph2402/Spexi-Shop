export class ObjectUtil {
  static ignoreNullValue(target: any): any {
    let result: any = {};
    for (let i in target) {
      if (target[i] != null || (typeof target[i] == 'string' && target[i] == '')) {
        result[i] = target[i];
      }
    }
    return result;
  }

  static mergeValue(target: any, source: any, props: string[] | string = null): any {
    let result: any = {};
    source = this.ignoreNullValue(source);
    if (props) {
      if (typeof props == 'string') {
        props = [props];
      }
      for (let i in target) {
        result[i] = target[i];
        if (props.indexOf(i) >= 0 && source.hasOwnProperty(i)) {
          result[i] = source[i];
        }
      }
    } else {
      result = this.clone(target);
      for (let i in target) {
        result[i] = target[i];
        if (source.hasOwnProperty(i)) {
          result[i] = source[i];
        }
      }
    }
    return result;
  }

  static combineValue(target: any, source: any, ignoreNull: boolean = true): any {
    if (ignoreNull) {
      source = this.ignoreNullValue(source);
    }
    return Object.assign(target, source);
  }

  static clone(source: any) {
    return Object.assign({}, source);
  }
}
