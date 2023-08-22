export class ModalResult<T> {
  data: T;
  success: boolean;

  constructor(data?: any) {
    if (data) {
      this.success = true;
      this.data = data;
    } else {
      this.success = false;
    }
  }
}
