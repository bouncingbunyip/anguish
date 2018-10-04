<?php
    

/**
 * Mary had a little lamb,
 * whose "fleece" wasn't white as snow.
 *
 * Merry hidy ladle limb,
 * house "fleas" worstedn't wheat axe know.
 *
 */
 

$string = <<<'EOD'
Mary had a little lamb,
whose "fleece" wasn't white as snow.
EOD;

$expect = <<<'EOD'
Merry hidy ladle limb,
house "fleas" worstedn't wheat axe know.
EOD;
$lexicon = array(
    'mary' => array('merry'),
    'had-a' => array('hidy'),
    'little'=> array('ladle'),
    'lamb' => array('limb'),
    'whose' => array('house'),
    'fleece' => array('fleas'),
    'wasn&apos;t' => array('worstene&apos;t'),
    'white' => array('wheat'),
    'as-snow' => array('axe know')
);

$rs = translate($string, $lexicon);
out ($rs);
exit;

/**
* convert spaces to |&nbps;| and newlines to |&#10:|
* convert punctuation to htmlentity version, with pipes | 
* convert contractions to htmlentity version, which should match the values in the lexicon
* if I have double pipes || convert to single pipe
* look for compound words like 'had-a' in the string and convert them to singles
* convert string to array, using | as the separator
* loop through the array looking for words that have the first letter capitalized and preserve the capitalization
* 
*/
function translate($string, $lexicon) {
    out($string);
    $clean = handleSpaces($string);
//    out($clean);
    $clean = handlePunctuation($clean);
//    out($clean);
    $clean = handleContractions($clean);
//    out($clean);
    $clean = removeDoublePipes($clean);
    out($clean);
    $clean = handleCompounds($clean, getCompoundsFromLexicon($lexicon));
    out($clean);
    $array = convertToArray($clean);
    $clean = transliterate($array, $lexicon);
    $clean = convertToString($clean);
    $clean = removePlaceholders($clean);
    out($clean);
    exit;
    return $clean;
}

function handleSpaces($string) {
    $replace = '|&nbsp;|';
    $clean = str_replace(' ', $replace, $string);
    $search = PHP_EOL;
    $replace = '|&#10;|';
    $clean = str_replace($search, $replace, $clean);
    return $clean;
}

function handlePunctuation($string) {
    $search = array('"', ',', '.');
    $replace = array('|&quot;|','|&comma;|', '|&period;|');
    $clean = str_replace($search, $replace, $string);
    return $clean;
}

function handleContractions($string) {
    $search = array("'");
    $replace = array('&apos;');
    $clean = str_replace($search, $replace, $string);
    return $clean;
}

function removeDoublePipes($string) {
    $search = '||';
    $replace = '|';
    return str_replace($search, $replace, $string);
}

function handleCompounds($string, $compounds) {
    out($string);
    out($compounds);
    foreach ($compounds as $compound) {
        $search[] = str_replace('-', '|&nbsp;|', $compound);
        $replace[] = $compound;
    }
    return str_replace($search, $replace, $string);
}

function convertToArray($string) {
    $tokens = explode('|', $string);
    return $tokens;
}

function transliterate($array, $lexicon) {
    $ignores = ignores();
    foreach ($array as $token) {
        if (empty($token)) {
            continue;
        }
        if(in_array($token, $ignores)) {
            $retval[]= $token;
        } elseif(array_key_exists(strtolower($token), $lexicon)) {
            if (ctype_upper( $token[0] )) {
                // first character is upper case
                $retval[] = ucfirst($lexicon[strtolower($token)][0]);
            } else {
                $retval[]= $lexicon[strtolower($token)][0];
            }
        } else {
            // we have an words that is not in the lexicon
            $retval[] = "[[$token]]";
        }
    }
    return $retval;
}

function convertToString($array) {
    return implode('|', $array);
}

function removePlaceholders($string) {
    $search = array('&nbsp;', '&comma;', '&#10;', '&quot;','&apos;', '&period;', '|');
    $replace = array(' ', ',', PHP_EOL, '"', '\'', '.', '');
    return str_replace($search, $replace, $string);
}

function getCompoundsFromLexicon($lexicon) {
    $keys = array_keys($lexicon);
    $rs = array();
    foreach ($keys as $key) {
        if (strpos($key, '-')) {
            $rs[] = $key;
        }
    }
    return $rs;
}

function ignores() {
    return array('&nbsp;', '&comma;', '&#10;', '&quot;','&apos;', '&period;');
}

function out($string) {
    echo '<pre>';
    var_dump($string);
    echo '</pre>'. PHP_EOL;
}
?>