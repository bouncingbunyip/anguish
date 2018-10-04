<?php
/**
 * Example.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */

namespace Anguish;


class Example
{

    public function __construct()
    {

    }

    public function loadExample($p) {
        $example = $this->examples($p);
        if (!$example) {
            return false;
        }
        $text = wordwrap(file_get_contents('./'.$example['src']), 100, "\n", false);
        if(array_key_exists('trans', $example)) {
            $translation = wordwrap(file_get_contents('./'.$example['trans']),100,"\n",false);
        } else {
            $translation = '';
        }
        $retval = array($example['title'], $text, $translation);
        return $retval;
    }

    public function examples($p) {
        $examples = array(
            'carloons' => array('title'=>'Carloons',                     'src'=>'canon/carloons.anguish', 'trans'=>'canon/carloons.english'),
            'center'   => array('title'=>'Center Alley',                 'src'=>'canon/center.anguish', 'trans'=>'canon/center.english'),
            'fey'      => array('title'=>'Fey Mouse Tells',              'src'=>'canon/fey-mouse.anguish', 'trans'=>'fey-mouse.english'),
            'genesis'  => array('title'=>'Gender Cyst',                  'src'=>'canon/genesis.anguish', 'trans'=>'canon/genesis.english'),
            'guilty'   => array('title'=>'Guilty Looks Enter Tree Beers','src'=>'canon/guilty.anguish', 'trans'=>'canon/guilty.english'),
            'lath'     => array('title'=>'Lath Thing Thumb Thongs!',     'src'=>'canon/lath-thing.anguish', 'trans'=>'lath-thing.english'),
            'noisier'  => array('title'=>'Noisier Rams',                 'src'=>'canon/noisier.anguish', 'trans'=>'canon/noisier.english'),
            'rotten'   => array('title'=>'Ladle Rat Rotten Hut',         'src'=>'canon/rotten.anguish', 'trans'=>'canon/rotten.english'),
        );
        if (array_key_exists($p, $examples)) {
            return $examples[$p];
        }
        return false;
    }
}