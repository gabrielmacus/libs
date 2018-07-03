<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 18/06/2018
 * Time: 23:55
 */

$_ENV["app"]["jwt"]["key"] = 'J#)BAKPp!z882#sA';
$_ENV["app"]["jwt"]["exp"] = 60 * 60 *15; //15 days
$_ENV["app"]["url"] =  sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    ''
);
$_ENV["app"]["name"] = "";
