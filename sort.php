<?php
/**
 * sort.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */

/**
 * should not need this again.  it was used to clean up old lexicons 
*/

die();

// namespace Anguish;
//
// require_once 'app/Utils.php';
// require_once 'dictionaries/lexicon.php';
//
// $util = new Utils();
//
// ksort($englishToAnguish, SORT_STRING);
// $string = '<?php
// /**
//  * English
//  * key is the english word or phrase
//  * value is the anguish word or phrase
//  */
// ';
//
// $string .= getBlock($englishToAnguish, 'english');
// $string .= '
//
// /**
//  * Anguish
//  * key is the anguish word or phrase
//  * value is the english word or phrase
//  */
// ';
// $string .= getBlock($anguishToEnglish, 'anguish');
//
// $string .= '
// ?>';
//
// $rs = file_put_contents('dictionaries/test.php', $string);
//
// var_dump($rs);
//
//
//
// function getBlock($array, $version) {
//     if ($version == 'anguish') {
//         $var = 'anguishToEnglish';
//     } else {
//         $var = 'englishToAnguish';
//     }
//     $contents = '';
//
//     foreach ($array as $key => $values) {
//         $value = implode("', '", $values);
//         $contents .= '    \''. $key .'\' => array(\''. $value .'\'),'.PHP_EOL;
//     }
//     $block = '$'.$var .' = array(
// '. $contents .');';
//     return $block;
// }
//
