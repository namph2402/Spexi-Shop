export class Select2Config extends Object {
  singleSelection: boolean;//true: single, false:multi
  text: string;//placeHolder
  enableCheckAll: boolean;//
  selectAllText: string;//'Chọn tất cả'
  unSelectAllText: string;//'Bỏ chọn tất cả'
  enableSearchFilter: boolean;
  enableFilterSelectAll: boolean;
  searchPlaceholderText: string;//'Tìm kiếm'
  labelKey: string;//name
  primaryKey: string;//'id-system'
  searchBy: string[];//['name_with_machine']


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
      searchBy: ['name'],
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

