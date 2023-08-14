import * as moment from 'moment';

export class DateTimeUtil {

  static DB_DATE_FORMAT = 'YYYY-MM-DD';
  static DB_TIME_FORMAT = 'HH:mm:ss';
  static DB_DATE_TIME_FORMAT = 'YYYY-MM-DD HH:mm:ss';

  static parse(str: string) {
    let date = moment(str);
    return date.isValid() ? date.format(this.DB_DATE_TIME_FORMAT) : null;
  }

  static today(format: string = this.DB_DATE_FORMAT) {
    return moment().format(format);
  }

  static yesterday(format: string = this.DB_DATE_FORMAT) {
    return moment().subtract(1, 'days').format(format);
  }

  static agoDays(days: number, format: string = this.DB_DATE_FORMAT) {
    return moment().subtract(days, 'days').format(format);
  }

  static startOfThisMonth(format: string = this.DB_DATE_FORMAT) {
    return moment().startOf('month').format(format);
  }

  static endOfThisMonth(format: string = this.DB_DATE_FORMAT) {
    return moment().endOf('month').format(format);
  }

  static startOfLastMonth(format: string = this.DB_DATE_FORMAT) {
    return moment().subtract(1, 'months').startOf('month').format(format);
  }

  static endOfLastMonth(format: string = this.DB_DATE_FORMAT) {
    return moment().subtract(1, 'months').endOf('month').format(format);
  }

}
