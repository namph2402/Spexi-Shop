export class NumberInputConfig extends Object {
  isCurrency: boolean;
  maxValue: number;
  minValue: number;
  step: number;

  static create(): NumberInputConfig {
    return {
      isCurrency: false,
      maxValue: null,
      minValue: null,
      step: null
    };
  }
}
