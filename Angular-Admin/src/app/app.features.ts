export const APP_FEATURES = [
  {
    name: 'Bảng điều khiển',
    icon: 'fa fa-dashboard',
    link: '/dashboard'
  },
  {
    name: 'Quản lý chung',
    icon: 'fa fa-list',
    children:
      [
        {
          name: 'Thông tin cửa hàng',
          icon: 'fa fa-building-o',
          link: '/store'
        },
        {
          name: 'Quản lý nhân viên',
          icon: 'fa fa-users',
          link: '/staffs'
        },
        {
          name: 'Quản lý khách hàng',
          icon: 'fa fa-users',
          children: [
            {
              name: 'Tài khoản',
              icon: 'fa fa-user',
              link: '/customers'
            },
            {
              name: 'Quản lý dữ liệu',
              icon: 'fa fa-square',
              link: '/form-datas'
            }
          ]
        },
        {
          name: 'Quản lý giao dịch',
          icon: 'fa fa-money',
          children: [
            {
              name: 'Phương thức thanh toán',
              icon: 'fa fa-credit-card',
              link: '/payment_methods'
            },
            {
              name: 'Giao dịch thanh toán',
              icon: 'fa fa-handshake-o',
              link: '/payment-transactions'
            },
            {
              name: 'Giao dịch chi tiêu',
              icon: 'fa fa-money',
              link: '/expenses'
            }
          ]
        },
      ]
  },
  {
    name: 'Quản lý giao diện',
    icon: 'fa fa-desktop',
    children: [
      {
        name: 'Banner',
        icon: 'fa fa-picture-o',
        link: '/banners'
      },
      {
        name: 'Menu',
        icon: 'fa fa-bars',
        link: '/menus'
      },
      {
        name: 'Bài viết',
        icon: 'fa fa-wpforms',
        link: '/store_posts'
      },
    ]
  },
  {
    name: 'Quản trị website',
    icon: 'fa fa-chrome',
    children: [
      {
        name: 'Quản lý bài đăng',
        icon: 'fa fa-wpforms',
        children: [
          {
            name: 'Danh mục',
            icon: 'fa fa-bars',
            link: '/post-categories'
          },
          {
            name: 'Bài viết',
            icon: 'fa fa-wpforms',
            link: '/posts'
          },
          {
            name: 'Tag',
            icon: 'fa fa-tag',
            link: '/post-tags'
          }
        ],
      },
      {
        name: 'Quản lý sản phẩm',
        icon: 'fa fa-product-hunt',
        children: [
          {
            name: 'Danh mục',
            icon: 'fa fa-bars',
            link: '/product-categories'
          },
          {
            name: 'Sản phẩm',
            icon: 'fa fa-product-hunt',
            link: '/products'
          },

          {
            name: 'Biến thể',
            icon: 'fa fa-vimeo',
            link: '/variants'
          },
          {
            name: 'Tag',
            icon: 'fa fa-tag',
            link: '/product-tags'
          }
        ],
      },
      {
        name: 'Quản lý thông báo',
        icon: 'fa fa-bell',
        link: '/notifications'
      },
      {
        name: 'Mã giảm giá',
        icon: 'fa fa-gift',
        link: '/vouchers'
      },
      {
        name: 'Chương trình khuyến mãi',
        icon: 'fa fa-bullhorn',
        link: '/promotions'
      }
    ]
  },
  {
    name: 'Quản lý bán hàng',
    icon: 'fa fa-list-alt',
    children: [
      {
        name: 'Đơn hàng',
        icon: 'fa fa-bars',
        link: '/orders'
      },
      {
        name: 'Đơn vận',
        icon: 'fa fa-truck',
        link: '/order-ships'
      },
      {
        name: 'Đối tác vận chuyển',
        icon: 'fa fa-handshake-o',
        link: '/ship-units'
      },
      {
        name: 'Địa chỉ vận chuyển',
        icon: 'fa fa-map',
        children: [
          {
            name: 'Địa chỉ',
            icon: 'fa fa-map-marker',
            link: '/provinces'
          },
          {
            name: 'Phí vận chuyển',
            icon: 'fa fa-motorcycle',
            link: '/shipping_fees'
          },
        ]
      },
    ]
  },
  {
    name: 'Quản lý kho hàng',
    icon: 'fa fa-university',
    children: [
      {
        name: 'Kho hàng',
        icon: 'fa fa-archive',
        link: '/warehouses'
      },
      {
        name: 'Phiếu kho',
        icon: 'fa fa-wpforms',
        link: '/imports'
      }
    ]
  },
];
