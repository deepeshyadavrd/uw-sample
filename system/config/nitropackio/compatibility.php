<?php

$_['nitropackio'] = array(
    'version' => '3.9.3',
    'modified_file' => 'catalog/controller/extension/module/nitropack.php',
    'oc' => array(
        'setting_code' => 'module_nitropack',
        'field' => array(
            'status' => 'module_nitropack_status'
        ),
        'route' => array(
            'extension' => 'marketplace/extension',
            'modification' => 'marketplace/modification',
            'theme_cache' => 'common/developer'
        )
    ),
    'route' => array(
        'event' => array(
            'category' => 'extension/module/nitropack_event_category',
            'information' => 'extension/module/nitropack_event_information',
            'manufacturer' => 'extension/module/nitropack_event_manufacturer',
            'product' => 'extension/module/nitropack_event_product',
            'review' => 'extension/module/nitropack_event_review',
            'theme' => 'extension/module/nitropack_event_theme'
        ),
        'module' => array(
            'nitropack' => 'extension/module/nitropack'
        )
    ),
    'model' => array(
        'event' => array(
            'category' => 'model_extension_module_nitropack_event_category',
            'information' => 'model_extension_module_nitropack_event_information',
            'manufacturer' => 'model_extension_module_nitropack_event_manufacturer',
            'product' => 'model_extension_module_nitropack_event_product',
            'review' => 'model_extension_module_nitropack_event_review',
            'theme' => 'model_extension_module_nitropack_event_theme'
        ),
        'module' => array(
            'nitropack' => 'model_extension_module_nitropack'
        )
    ),
    'event' => array(
        // Catalog Cart
        'catalog/controller/common/cart/after' => array('extension/module/nitropack/cartPlaceholder'),
        // Catalog Products
        'catalog/model/catalog/product/getProduct/after' => array('extension/module/nitropack/afterGetProduct'),
        'catalog/model/catalog/product/getProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/catalog/product/getProductRelated/after' => array('extension/module/nitropack/afterGetProducts'),
        // Journal3 Async Controllers
        'catalog/controller/journal3/main_menu/before' => array('extension/module/nitropack/beforeAsyncElement'),
        'catalog/controller/journal3/products/before' => array('extension/module/nitropack/beforeAsyncElement'),
        // OpenCart 3 menu
        'catalog/controller/common/menu/before' => array('extension/module/nitropack/beforeAsyncElement'),
        // Journal3 Products
        'catalog/model/journal3/filter/getProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getProduct/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getRelatedProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getRelatedProductsByCategory/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getRelatedProductsByManufacturer/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getAlsoBoughtProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        'catalog/model/journal3/product/getMostViewedProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        // Catalog Categories
        'catalog/model/catalog/category/getCategory/after' => array('extension/module/nitropack/afterGetCategory'),
        // Catalog Manufacturers
        'catalog/model/catalog/manufacturer/getManufacturer/after' => NITROPACK_DISABLE_MANUFACTURER_TAGGING ? array() : array('extension/module/nitropack/afterGetManufacturer'),
        'catalog/model/catalog/manufacturer/getManufacturers/after' => NITROPACK_DISABLE_MANUFACTURER_TAGGING ? array() : array('extension/module/nitropack/afterGetManufacturers'),
        // Catalog Informations
        'catalog/model/catalog/information/getInformation/after' => array('extension/module/nitropack/afterGetInformation'),
        // Order Histories
        'catalog/model/checkout/order/addOrder/before' => array('extension/module/nitropack/beforeAddOrder'),
        'catalog/model/checkout/order/editOrder/before' => array('extension/module/nitropack/beforeModifyOrder'),
        'catalog/model/checkout/order/deleteOrder/before' => array('extension/module/nitropack/beforeModifyOrder'),
        'catalog/model/checkout/order/addOrderHistory/before' => array('extension/module/nitropack/beforeModifyOrder'),
        // Admin Products
        'admin/model/catalog/product/addProduct/after' => array('extension/module/nitropack/productAfterAdd'),
        'admin/model/catalog/product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product/deleteProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product/deleteProduct/after' => array('extension/module/nitropack/productAfterDelete'),
        'admin/model/catalog/product/copyProduct/after' => array('extension/module/nitropack/productAfterCopy'),
        // Quick Edit Product
        'admin/model/extension/module/aqe/catalog/product/quickEditProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/extension/module/aqe/catalog/product/quickEditProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/extension/module/product_quick_edit/quickEditProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/extension/module/product_quick_edit/quickEditProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/quick_product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/quick_product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        // Product Import Suite
        'admin/model/importer/product/addProduct/after' => array('extension/module/nitropack/productAfterCopy'),
        'admin/model/importer/product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/importer/product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/importer/product/addManufacturer/after' => array('extension/module/nitropack/manufacturerAfterAdd'),
        'admin/model/importer/product/addCategory/after' => array('extension/module/nitropack/categoryAfterAdd'),
        // Admin Categories
        'admin/model/catalog/category/addCategory/after' => array('extension/module/nitropack/categoryAfterAdd'),
        'admin/model/catalog/category/editCategory/before' => array('extension/module/nitropack/categoryBeforePersist'),
        'admin/model/catalog/category/editCategory/after' => array('extension/module/nitropack/categoryAfterEdit'),
        'admin/model/catalog/category/deleteCategory/before' => array('extension/module/nitropack/categoryBeforePersist'),
        'admin/model/catalog/category/deleteCategory/after' => array('extension/module/nitropack/categoryAfterDelete'),
        // Admin Manufacturers
        'admin/model/catalog/manufacturer/addManufacturer/after' => array('extension/module/nitropack/manufacturerAfterAdd'),
        'admin/model/catalog/manufacturer/editManufacturer/before' => array('extension/module/nitropack/manufacturerBeforePersist'),
        'admin/model/catalog/manufacturer/editManufacturer/after' => array('extension/module/nitropack/manufacturerAfterEdit'),
        'admin/model/catalog/manufacturer/deleteManufacturer/before' => array('extension/module/nitropack/manufacturerBeforePersist'),
        'admin/model/catalog/manufacturer/deleteManufacturer/after' => array('extension/module/nitropack/manufacturerAfterDelete'),
        // Admin Informations
        'admin/model/catalog/information/addInformation/after' => array('extension/module/nitropack/informationAfterAdd'),
        'admin/model/catalog/information/editInformation/before' => array('extension/module/nitropack/informationBeforePersist'),
        'admin/model/catalog/information/editInformation/after' => array('extension/module/nitropack/informationAfterEdit'),
        'admin/model/catalog/information/deleteInformation/before' => array('extension/module/nitropack/informationBeforePersist'),
        'admin/model/catalog/information/deleteInformation/after' => array('extension/module/nitropack/informationAfterDelete'),
        // Admin Reviews
        'admin/model/catalog/review/addReview/after' => array('extension/module/nitropack/reviewAfterAdd'),
        'admin/model/catalog/review/editReview/before' => array('extension/module/nitropack/reviewBeforePersist'),
        'admin/model/catalog/review/editReview/after' => array('extension/module/nitropack/reviewAfterEdit'),
        'admin/model/catalog/review/deleteReview/before' => array('extension/module/nitropack/reviewBeforePersist'),
        'admin/model/catalog/review/deleteReview/after' => array('extension/module/nitropack/reviewAfterDelete'),
        // Journal 2 & 3 Theme
        'admin/model/journal2/modules/save/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal2/modules/save/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal2/modules/add/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal2/modules/add/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal2/modules/edit/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal2/modules/edit/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal2/modules/remove/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal2/modules/remove/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal2/modules/duplicate/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal2/modules/duplicate/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal3/module/add/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal3/module/add/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal3/module/edit/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal3/module/edit/after' => array('extension/module/nitropack/themeAfterPersist'),
        'admin/model/journal3/module/delete/before' => array('extension/module/nitropack/themeBeforePersist'),
        'admin/model/journal3/module/delete/after' => array('extension/module/nitropack/themeAfterPersist'),
        // ExcelPort Products
        'admin/model/extension/module/excelport_product/addProduct/after' => array('extension/module/nitropack/productAfterAdd'),
        'admin/model/extension/module/excelport_product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/extension/module/excelport_product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/extension/module/excelport_product_bulk/addProduct/after' => array('extension/module/nitropack/productAfterAdd'),
        'admin/model/extension/module/excelport_product_bulk/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/extension/module/excelport_product_bulk/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        // Admin POS
        'admin/model/pos/order/addOrder/before' => array('extension/module/nitropack/beforeAddOrder'),
        'admin/model/pos/order/editOrder/before' => array('extension/module/nitropack/beforeModifyOrder'),
        // Instant Product Editor - https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=1561
        'admin/model/catalog/product_extra/changeProductToStore/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductToStore/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductStatus/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductStatus/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductSubstract/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductSubstract/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductQuantity/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductQuantity/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductPrice/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductPrice/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeSpecialPrices/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeSpecialPrices/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductCategory/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductCategory/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductSortOrder/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductSortOrder/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeManufacturer/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeManufacturer/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeSeo/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeSeo/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeModel/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeModel/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeImage/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeImage/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeName/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeName/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeMetaTitle/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeMetaTitle/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductSku/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductSku/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeUpc/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeUpc/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeLocation/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeLocation/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeDateAvailable/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeDateAvailable/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeWeight/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeWeight/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeWeightClass/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeWeightClass/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeTaxClass/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeTaxClass/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeStockStatus/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeStockStatus/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeLength/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeLength/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeWidth/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeWidth/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeHeight/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeHeight/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeLengthClass/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeLengthClass/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductSpecialPrice/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductSpecialPrice/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product_extra/changeProductDiscountPrice/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product_extra/changeProductDiscountPrice/after' => array('extension/module/nitropack/productAfterEdit'),
        // Admin Languages
        'admin/model/localisation/language/addLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        'admin/model/localisation/language/editLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        'admin/model/localisation/language/deleteLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        // Admin Settings
        'admin/model/setting/setting/editSetting/after' => array('extension/module/nitropack/updateDefaults'),
        // Admin Menu
        'admin/view/common/column_left/before' => array('extension/module/nitropack/menuItem'),
        // Manual Uninstall
        'admin/model/setting/extension/getExtensionPathsByExtensionInstallId/before' => array('extension/module/nitropack/manualUninstall'),
    )
);
