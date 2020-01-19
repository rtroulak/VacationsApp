<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 19/01/2020
 */

header('Content-Type: text/html; charset=utf-8');
/**
 * @param $data
 * @return mixed
 */
function defSqlInjection($data)
{
    $data = str_replace("'", "", $data);
    $data = str_replace("\"", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("<", "", $data);
    $data = str_replace(">", "", $data);
    $data = str_replace("select ", "", $data);
    
    return $data;
}

/**
 * @param $data
 * @return string
 */
function defXSS($data)
{
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    return $data;
}

/**
 * The code replaces XSS malicious characters without replacing the characters < >,
 * in order to be used for HTML texts in cooperation with defJavascript() function
 * @param $data
 * @return mixed
 */
function defXSSForHtml($data)
{
    $str = str_replace("&", "&amp;", $data);
    $str = str_replace("\"", "&quot;", $str);
    $str = str_replace("'", "&#x27;", $str);

    return $str;
}


/**
 *   The code replaces XSS malicious characters without replacing the characters < > & ,
 * in order to be used fornames
 * @param $data
 * @return mixed
 */
function defXSSForNames($data)
{

    $str = str_replace("\"", "&quot;", $data);
    $str = str_replace("'", "&#x27;", $str);

    return $str;
}


/**
 *  The code replaces sql injection malicious specific characters
 * in order to be used for iput names
 * @param $input
 * @return mixed
 */
function defSqlInjectionForNames($data)
{
    $data = str_replace("'", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("<", "", $data);
    $data = str_replace(">", "", $data);
    $data = str_replace("select ", "", $data);

    return $data;
}

/**
 * @param $data
 * @return mixed
 */
function defPathTraversal($data)
{
    $input = str_replace("../", "", $data);
    $input = str_replace("/", "", $data);
    $input = str_replace(".php", "", $data);
    $input = str_replace(".pdf", "", $data);
    $input = str_replace(".txt", "", $data);
    $input = str_replace(".js", "", $data);
    $input = str_replace("var/", "", $data);
    $input = str_replace("www/", "", $data);
    $input = str_replace("vhosts/", "", $data);
    $input = str_replace("top.host/", "", $data);

    return $data;
}

/**
 * @param $data
 * @return mixed
 */
function defJavascript($data)
{
    $data = str_replace("script", "", $data);
    $data = str_replace("javascript", "", $data);

    return $data;
}

function defHttpRedirect($data){
    $data=urldecode($data);
    $data = str_replace("http://", "", $data);
    $data = str_replace("https://", "", $data);
    $data = str_replace("//", "", $data);
    $data = str_replace("/\"", "", $data);

    return $data;
}

/**
 * @param $data
 * @return mixed
 * replaces brackets
 */
function defBrackets($data){
    $data = str_replace("{", "", $data);
    $data = str_replace("}", "", $data);
    return $data;

}

/**
 * @param $data
 * @return mixed
 */
function defCombo($data){
    return defPathTraversal(defJavascript(defBrackets(defSqlInjection(defXSS($data)))));
}


function status($status){
    if($status == 0 ) return 'Rejected';
    if($status == 1 ) return 'Pending';
    if($status == 2 ) return 'Approved';

    return 'NoStatus';
}