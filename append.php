<?php
require_once 'dictionaries/lexicon.php';

$data = file_get_contents('dictionaries/append.txt');

$confirm = $_GET['confirm'] ?? 'false';

if ($confirm == 'false') {
    echo '<pre>This script will look in the append.txt file for values to append to the lexicon.'. PHP_EOL .'
The append.txt file should have one entry per line in this format: english,anguish'. PHP_EOL .'
words with an apostrophe should have it replaced with &apos;, for example: isn\'t should be isn&apos;t '.PHP_EOL .'
and phrases should have the whitespace replaced with a hyphen, for example: dig dug should be dig-dug ' .PHP_EOL .'
Both entries will be added to the lexicon.'. PHP_EOL .'
'. PHP_EOL .'
The current version of the lexicon will be copied to lexicon.orig'. PHP_EOL .'
The new version of the lexicon will be lexicon.php'. PHP_EOL .'
<a href="append.php?confirm=true">click this link to continue</a></pre>';
    exit;
}

if ($data) {
    $lines = explode("\n", $data);
    foreach ($lines as $line) {
        // need to ignore the comments which start with a '#'
        $pairs = explode(",", $line);
        if (isset($pairs[1])) {
            $dict[$pairs[0]] = $pairs[1];
        }
    }
}
$lex['englishToAnguish'] = updateLexicon($englishToAnguish, $dict);
$lex['anguishToEnglish'] = updateLexicon($anguishToEnglish, array_flip($dict));
$backup = backupExistingLexicon();
if ($backup) {
    $rs = writeLexicon($lex);
} else {
    $rs = 'did not write lexicon as backup failed.';
}

out($rs); exit;

/**--------------------------------------------------*/

function updateLexicon($section, $new) {
    $existing = array_keys($section);
    foreach ($new as $key => $value) {
        if (in_array($key, $existing)) {
            // see if the existing entry has this new value
            if (!in_array($value, $section[$key])) {
                array_push($section[$key], $value);
            }
        } else {
            // the new value is not in the lexicon so add it
            $section[$key] = array($value);
//            array_push($section,array($key=>array($value)));
        }
    }
    ksort($section);
    return $section;
}

function writeLexicon($data) {
    $string = '<?php
    /**
     * English
     * key is the english word or phrase
     * value is the anguish word or phrase
     */
    ';

    $string .= getBlock($data['englishToAnguish'], 'english');
    $string .= '
    
    /**
     * Anguish
     * key is the anguish word or phrase
     * value is the english word or phrase
     */
    ';
    $string .= getBlock($data['anguishToEnglish'], 'anguish');

    $string .= '
    ?>';
    
    $rs = file_put_contents('dictionaries/lexicon.php', $string);
    return $rs;
}

function getBlock($array, $version) {
    if ($version == 'anguish') {
        $var = 'anguishToEnglish';
    } else {
        $var = 'englishToAnguish';
    }
    $contents = '';
    
    foreach ($array as $key => $values) {
        $value = implode("', '", $values);
        $contents .= '    \''. $key .'\' => array(\''. $value .'\'),'.PHP_EOL;
    }
    $block = '$'.$var .' = array(
'. $contents .');';
    return $block;
}

function backupExistingLexicon() {
    if(!copy ('dictionaries/lexicon.php','dictionaries/lexicon.orig')) {
        echo 'failed to copy lexicon';
        return false;
    }
    return true;
}

function out($text) {
    echo '<pre>';
    var_dump($text);
    echo '</pre>';
}

?>