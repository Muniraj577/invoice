<?php
/**
 * Created by PhpStorm.
 * User: pirat
 * Date: 2020-04-07
 * Time: 11:08 AM
 */

if (!function_exists('generateProductCode')) {
    function generateProductCode()
    {
        $latestProductCode = App\Product::latest()->first();
        if (!$latestProductCode) {
            return 'Pro#0001';
        }
        return 'Pro#' . sprintf('%04d', $latestProductCode->id + 1);
    }

}

if (!function_exists('generateInvoiceCode')) {
    function generateInvoiceCode()
    {
        $latestInvoiceCode = App\Invoice::latest()->first();
        if (!$latestInvoiceCode) {
            return date('Y-m') . '-' . 'INV#0001';
        }
        return date('Y-m') . '-' . 'INV#' . sprintf('%04d', $latestInvoiceCode->id + 1);
    }

}