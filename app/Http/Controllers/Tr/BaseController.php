<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 10.01.2019
 * Time: 22:41
 */

namespace App\Http\Controllers\Tr;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Country;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function __construct()
    {
        $AUTH_USER = ['musa', 'kamil', 'sahib1'];
        $AUTH_PASS = 'q1w2e3r4';
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            !in_array($_SERVER['PHP_AUTH_USER'], $AUTH_USER) ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
    }
}