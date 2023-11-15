export const MODULES_ROUTING = [
  {
    path: '',
    redirectTo: 'dashboard',
    pathMatch: 'full'
  },
  {
    path: 'dashboard',
    loadChildren: 'app/modules/dashboard/dashboard.module#DashboardModule'
  },
  {
    path: 'notifications',
    loadChildren: 'app/modules/notification/notification.module#NotificationModule'
  },
  {
    path: 'post-categories',
    loadChildren: 'app/modules/post-category/post-category.module#PostCategoryModule'
  },
  {
    path: 'posts',
    loadChildren: 'app/modules/post/post.module#PostModule'
  },
  {
    path: 'customers',
    loadChildren: 'app/modules/customer/customer.module#CustomerModule'
  },
  {
    path: 'staffs',
    loadChildren: 'app/modules/staff/staff.module#StaffModule'
  },
  {
    path: 'warehouses',
    loadChildren: 'app/modules/warehouse/warehouse.module#WarehouseModule'
  },
  {
    path: 'form-datas',
    loadChildren: 'app/modules/form-data/form-data.module#FormDataModule'
  },
  {
    path: 'post-tags',
    loadChildren: 'app/modules/post-tag/post-tag.module#PostTagModule'
  },
  {
    path: 'banners',
    loadChildren: 'app/modules/banner-group/banner-group.module#BannerGroupModule'
  },
  {
    path: 'menus',
    loadChildren: 'app/modules/menu-position/menu-position.module#MenuPositionModule'
  },
  {
    path: 'store',
    loadChildren: 'app/modules/store/store.module#StoreModule'
  },
  {
    path: 'product-categories',
    loadChildren: 'app/modules/product-category/product-category.module#ProductCategoryModule'
  },
  {
    path: 'products',
    loadChildren: 'app/modules/product/product.module#ProductModule'
  },
  {
    path: 'product-tags',
    loadChildren: 'app/modules/product-tag/product-tag.module#ProductTagModule'
  },
  {
    path: 'orders',
    loadChildren: 'app/modules/order/order.module#OrderModule'
  },
  {
    path: 'order-ships',
    loadChildren: 'app/modules/order-ship/order-ship.module#OrderShipModule'
  },
  {
    path: 'ship-units',
    loadChildren: 'app/modules/shipping-unit/shipping-unit.module#ShippingUnitModule'
  },
  {
    path: 'vouchers',
    loadChildren: 'app/modules/voucher/voucher.module#VoucherModule'
  },
  {
    path: 'promotions',
    loadChildren: 'app/modules/promotion/promotion.module#PromotionModule'
  },
  {
    path: 'shipping_fees',
    loadChildren: 'app/modules/shipping-fee/shipping-fee.module#ShippingFeeModule'
  },
  {
    path: 'variants',
    loadChildren: 'app/modules/variant/variant.module#VariantModule'
  },
  {
    path: 'store_posts',
    loadChildren: 'app/modules/store-post/store-post.module#StorePostModule'
  },
  {
    path: 'imports',
    loadChildren: 'app/modules/import/import.module#ImportModule'
  },
  {
    path: 'expenses',
    loadChildren: 'app/modules/expense/expense.module#ExpenseModule'
  },
  {
    path: 'payment_methods',
    loadChildren: 'app/modules/payment-method/payment-method.module#PaymentMethodModule'
  },
  {
    path: 'payment-transactions',
    loadChildren: 'app/modules/payment-transaction/payment-transaction.module#PaymentTransactionModule'
  },
  {
    path: 'profile',
    loadChildren: 'app/modules/profile/profile.module#ProfileModule'
  }
];
