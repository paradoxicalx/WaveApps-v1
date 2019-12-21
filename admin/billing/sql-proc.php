<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();

include "../../assets/func/sql/billing/account/account.php";
include "../../assets/func/sql/billing/account/new-account.php";
include "../../assets/func/sql/billing/account/edit-account.php";
include "../../assets/func/sql/billing/account/remove-account.php";
include "../../assets/func/sql/billing/account/transfer.php";

include "../../assets/func/sql/billing/sales/invoice.php";
include "../../assets/func/sql/billing/sales/new-invoice.php";
include "../../assets/func/sql/billing/sales/invoice-print.php";
include "../../assets/func/sql/billing/sales/pay-invoice.php";
include "../../assets/func/sql/billing/sales/remove-invoice.php";
include "../../assets/func/sql/billing/sales/refund-invoice.php";
include "../../assets/func/sql/billing/sales/edit-invoice.php";

include "../../assets/func/sql/billing/sales/payment-report.php";
include "../../assets/func/sql/billing/sales/addwallet.php";

include "../../assets/func/sql/billing/transaction/new-transaction.php";
include "../../assets/func/sql/billing/transaction/transaction-list.php";

include "../../assets/func/sql/billing/report.php";
?>
