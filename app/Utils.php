<?php
/**
 * Utils.php
 *
 * @version $Id: $
 * @package VirtualInvite
 * @copyright 2011-2016
 */

namespace Anguish;


class Utils
{
    /**
     * assuming that we are receiving the lexicon with the keys as english and values as anguish
     * and returning a new lexicon with keys as anguish
     * note this will probably fail, as over time will have anguish keys with multiple transliterations
     */
    public function reverseLexicon($lexicon) {
        
    }
    

    public function sortDictionary($name, $test = false) {
        $contents = file_get_contents($name);
        $lines = explode("\n", trim(strtolower($contents)));
        sort($lines, SORT_STRING);
        if ($test) {
            $append = '.sorted';
        } else {
            $append = '';
        }
        $data = implode("\n", $lines);
        $rs['bytes'] = file_put_contents($name.$append, $data);
        return $rs;
    }

    public function removeDuplicates($name, $test = false) {
        $contents = file_get_contents($name);
        $lines = explode("\n", trim($contents));
        $dups = array_diff_assoc($lines, array_unique($lines));
        $temp = array_unique($lines, SORT_STRING);
        if ($test) {
            $append = '.no-dups';
        } else {
            $append = '';
        }
        $data = implode("\n", $temp);
        $rs['duplicates'] = $dups;
        $rs['bytes'] = file_put_contents($name.$append, $data);
        return $rs;
    }
}