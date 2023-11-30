export class Select2Config extends Object {
  singleSelection: boolean;
  text: string;
  enableCheckAll: boolean;
  selectAllText: string;
  unSelectAllText: string;
  enableSearchFilter: boolean;
  enableFilterSelectAll: boolean;
  searchPlaceholderText: string;
  labelKey: string;
  primaryKey: string;
  searchBy: string[];

  static createSingle(): Select2Config {
    return {
      singleSelection: true,
      text: 'Chọn một',
      enableCheckAll: true,
      selectAllText: 'Chọn tất cả',
      unSelectAllText: 'Bỏ chọn tất cả',
      enableSearchFilter: true,
      enableFilterSelectAll: true,
      searchPlaceholderText: 'Tìm kiếm',
      labelKey: 'name',
      primaryKey: 'id',
      searchBy: ['name','code'],
    };
  }

  static createMulti(): Select2Config {
    return {
      singleSelection: false,
      text: 'Chọn nhiều',
      enableCheckAll: true,
      selectAllText: 'Chọn tất cả',
      unSelectAllText: 'Bỏ chọn tất cả',
      enableSearchFilter: true,
      enableFilterSelectAll: true,
      searchPlaceholderText: 'Tìm kiếm',
      labelKey: 'name',
      primaryKey: 'id',
      searchBy: ['name'],
    };
  }

  static createSingle1(): Select2Config {
    return {
      singleSelection: true,
      text: 'Chọn một danh mục',
      enableCheckAll: true,
      selectAllText: 'Chọn tất cả',
      unSelectAllText: 'Bỏ chọn tất cả',
      enableSearchFilter: true,
      enableFilterSelectAll: true,
      searchPlaceholderText: 'Tìm kiếm',
      labelKey: 'name',
      primaryKey: 'id',
      searchBy: ['name'],
    };
  }
}

