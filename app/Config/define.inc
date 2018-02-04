<?php
define('ACCOUNT_TYPE_USER', 0);
define('ACCOUNT_TYPE_CUSTOMER', 1);
define('ACCOUNT_TYPE_MANAGER', 2);
define('ACCOUNT_TYPE_ADMIN', 3);
define('DEFAULT_LANGUAGE', 'en-us');
define('NO_IMAGE_URL', 'NoIMG');
define('EMAIL_DEFAULT', 'noreply@gmail.com');

define('ITEMS_PER_PAGE', 10);

//define api
define('API_SUCCESS', 1);
define('API_ERROR', 0);

define('ORDER_BUY', 0);
define('WAITTING_APPROVE', 1);
define('SUCCESS_BUY', 2);
define('CANCEL_BUY', 3);


//define user_type
define('CUSI_TYPE', 1);// người mua hàng
define('SASI_TYPE', 2);// người bán hàng sale
define('SISI_TYPE', 3); // người giao hàng trên sivi
define('COSI_TYPE', 4); // người chăm sóc khách hàng trên sivi
define('ADMIN_TYPE', 20);