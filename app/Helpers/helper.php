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