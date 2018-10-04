<?php
/**
 * Parser.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */

/**
 * consider exploring http://www.speech.cs.cmu.edu/cgi-bin/cmudict as a way of creating new homographic transformations
 */
namespace Anguish;

include_once 'Reference.php';

class Parser
{
    protected $debug = false;
    protected $debug_message;
    
    public function __construct() {}
        
    /**
    * convert spaces to |&nbps;| and newlines to |&#10:|
    * convert punctuation to htmlentity version, with pipes | 
    * convert contractions to htmlentity version, which should match the values in the lexicon
    * if I have double pipes || convert to single pipe
    * look for compound words like 'had-a' in the string and convert them to singles
    * convert string to array, using | as the separator
    * loop through the array looking for words that have the first letter capitalized and preserve the capitalization
    *
    * @param string string the string you want to translate
    * @param lexicon array the lexicon to use for the translation
    * @return string the translated string
    */
    public function translate($string, $lexicon) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $clean = $this->handlePunctuation($string);
        $clean = $this->handleSpaces($clean);
        $clean = $this->handleContractions($clean);
        $clean = $this->removeDoublePipes($clean);
        $clean = $this->handleCompounds($clean, $this->getCompoundsFromLexicon($lexicon));
        $array = $this->convertToArray($clean);
        $clean = $this->transliterate($array, $lexicon);
        $clean = $this->convertToString($clean);
        $clean = $this->removePlaceholders($clean);
        return $clean;
    }

    public function handleSpaces($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $replace = '|&nbsp;|';
        $clean = str_replace(' ', $replace, $string);
        $search = PHP_EOL;
        $replace = '|&#10;|';
        $clean = str_replace($search, $replace, $clean);
        $this->debug(__METHOD__ . ' returning: '. $clean);
        return $clean;
    }

    /**
    * we have to replace the semicolon first, as it's used in the html entities
    * not sure how to handle & and #
    */
    public function handlePunctuation($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $search = array(';', '"', ',', '.', ':', '!', '?','(', ')','—');
        $replace = array('|&semi;|','|&quot;|','|&comma;|', '|&period;|','|&colon;|','|&excl;|','|&quest;|','|&lpar;|','|&rpar;|', '|&mdash;|','|&colon;|');
        $clean = str_replace($search, $replace, $string);
        $this->debug(__METHOD__ . ' returning: '. $clean);
        return $clean;
    }

    public function handleContractions($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $search = array("'");
        $replace = array('&apos;');
        $clean = str_replace($search, $replace, $string);
        $this->debug(__METHOD__ . ' returning: '. $clean);
        return $clean;
    }

    public function removeDoublePipes($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $search = '||';
        $replace = '|';
        $clean = str_replace($search, $replace, $string);
        $this->debug(__METHOD__ . ' returning: '. $clean);
        return $clean;
    }

    /**
     * if the phrase we are looking for has first letter capitalized, need to handle that
     * for example both
     * alice and Alice should map to a-lace
    */
    public function handleCompounds($string, $compounds) {
        $this->debug(__METHOD__ . ' starting string: '. $string);
        $this->debug(__METHOD__ . ' starting compounds: '. implode(', ',$compounds));
        if (count($compounds) > 1) {
            foreach ($compounds as $compound) {
                $search = str_replace('-', '|&nbsp;|', $compound);
                $replace = $compound;
                $this->debug(__METHOD__ . ' replacing: '. $search .' with: '. $replace);
                if (ctype_upper($string[0])) {
//                    $this->debug(__METHOD__ . ' first letter is UC');
                    $token = strtolower($string);
//                    $this->debug(__METHOD__ . ' strtolower: '. $token);
                    $string = ucfirst(str_replace($search, $replace, $token));
                } else {
                    $string = str_replace('|'.$search, '|'.$replace, $string);
                }
//                $this->debug(__METHOD__ . ' updated string: '. $string);
            }
            $retval = $string;
            // $this->debug(__METHOD__ . ' search: '. implode(',', $search));
            // $this->debug(__METHOD__ . ' replace: '. implode(',', $replace));
            // $retval = str_replace($search, $replace, strtolower($string));
        } else {
            $retval = $string;
        }
        $this->debug(__METHOD__ . ' returning: '. '|'. $retval);
        return '|'.$retval;
    }

    public function convertToArray($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $tokens = explode('|', $string);
        return $tokens;
    }

    /**
     * the format of the lexicon matters
     * if I change the format, this function will not return expected results
     * I'm looking at the key of the lexicon array to see if I have a transliteration
    */
    public function transliterate($array, $lexicon) {
        $ignores = $this->ignores();
        foreach ($array as $token) {
            $token = trim($token);
            $this->debug(__METHOD__ . ' processing: '. $token);
            if (empty($token)) {
                $this->debug(__METHOD__ . ' empty token, continue');
                continue;
            }
            if(ctype_digit($token)) {
                $this->debug(__METHOD__ . ' numeric token, continue');
                $retval[]= $token;
            } elseif(in_array($token, $ignores)) {
                $this->debug(__METHOD__ . ' token is in ignores() ');
                $retval[]= $token;
            } elseif(array_key_exists(strtolower($token), $lexicon)) {
                if (ctype_upper($token[0])) {
                    // first character is upper case
                    $this->debug(__METHOD__ . ' first letter is uppercase');
                    $retval[] = ucfirst($lexicon[strtolower($token)][0]);
                } else {
                    $this->debug(__METHOD__ . ' first letter is not upppercase');
                    $retval[]= $lexicon[strtolower($token)][0];
                }
            } else {
                // we have an words that is not in the lexicon
                $this->debug(__METHOD__ . ' did not find token');
                $retval[] = "[[$token]]";
            }
        }
        return $retval;
    }

    public function convertToString($array) {
        $this->debug(__METHOD__ . ' started with: '. implode(', ',$array));
        $retval = implode('|', $array);
        $this->debug(__METHOD__ . ' returning: '. $retval);
        return $retval;
    }

    public function removePlaceholders($string) {
        $this->debug(__METHOD__ . ' started with: '. $string);
        $search = array('&nbsp;', '&comma;', '&#10;', '&quot;','&apos;', '&period;', '|', '-','&lpar;','&rpar;', '&mdash;', '&semi;', '&colon;','&excl;','&quest;');
        $replace = array(' ', ',', PHP_EOL, '"', '\'', '.', '', ' ','(', ')','—',';',':','!','?');
        $clean = str_replace($search, $replace, $string);
        $this->debug(__METHOD__ . ' returning: '. $clean);
        return $clean;
    }

    public function getCompoundsFromLexicon($lexicon) {
        $keys = array_keys($lexicon);
        $rs = array();
        foreach ($keys as $key) {
            if (strpos($key, '-')) {
                $rs[] = $key;
            }
        }
        return $rs;
    }

    public function ignores() {
        return array('&nbsp;', '&comma;', '&#10;', '&quot;','&apos;', '&period;','&lpar;','&rpar;', '&mdash;', '&semi;','&colon;','&quest;','&excl;');
    }
    
    public function setDebug($bool) {
        $this->debug = (bool)$bool;
    }

    public function debug($message) {
        if ($this->debug) {
            $this->debug_message[] = $message;
        }
    }

    public function getDebugMessages() {
        return $this->debug_message;
    }
}
