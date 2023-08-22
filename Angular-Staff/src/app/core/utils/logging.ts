import {environment} from '../../../environments/environment';

export class LoggingUtil {


  static d(msg: any): void {
    if (!environment.production) {
      console.log(msg);
    }
  }

}
