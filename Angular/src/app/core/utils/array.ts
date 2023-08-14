export class ArrayUtil {
  static isNotEmpty = (val) => {
    return (Array.isArray(val) && val.length > 0);

  };
}
