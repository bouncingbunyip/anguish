<?php
/**
 * ParserTest.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */

namespace Anguish;
require '../app/Parser.php';

use Anguish\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected $object;

    public function setUp() {
        $this->object = new Parser();
//        $this->object->setPathToDictionaries('../dictionaries/');
    }

    public function teadDown() {
        unset($this->object);
    }

    public function testGetPathToDictionaries() {
        // $expect = null;
        // $actual = $this->object->getPathToDictionaries();
        // $this->assertEquals($expect, $actual);
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
    
    public function testSetPathToDictionaries() {
        // $expect = null;
        // $actual = $this->object->getPathToDictionaries();
        // $this->assertEquals($expect, $actual);
        // $this->object->setPathToDictionaries('../dictionaries');
        // $expect = '../dictionaries';
        // $actual = $this->object->getPathToDictionaries();
        // $this->assertEquals($expect, $actual);
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testLoadDictionary() {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetDictionary() {
        // $dictionary = 'simple.txt';
        // $actual = $this->object->loadDictionary($dictionary, 'simple');
        // $this->assertNotEmpty($this->object->getDictionary('simple'));
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetDictionaryNoDictionary() {
//        $this->assertEmpty($this->object->getDictionary());
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    // public function testLoadDictionary() {
    //     $this->assertEmpty($this->object->getDictionary());
    //     $rs = $this->object->loadDictionary('simple.txt');
    //     $this->assertTrue($rs);
    //     $result = $this->object->getDictionary('simple');
    //     $this->assertTrue(count($result) > 0);
    // }

    public function testHandleSpaces() {
        $string = 'foo boo';
        $expect = 'foo|&nbsp;|boo';
        $actual = $this->object->handleSpaces($string);
        $this->assertEquals($expect, $actual);
    }

    public function testHandleSpacesWithNewline() {
        $string = 'foo goo
hoo koo';
        $expect = 'foo|&nbsp;|goo|&#10;|hoo|&nbsp;|koo';
        $actual = $this->object->handleSpaces($string);
        $this->assertEquals($expect, $actual);
    }
    
    public function testHandlePunctuation() {
        $string = 'foo!';
        $expect = 'foo|&excl;|';
        $actual = $this->object->handlePunctuation($string);
        $this->assertEquals($expect, $actual);
    }
    
    public function testHandlePunctuationAllOfThem() {
        $string = '",.:;!?';
        $expect = '|&quot;||&comma;||&period;||&colon;||&semi;||&excl;||&quest;|';
        $actual = $this->object->handlePunctuation($string);
        $this->assertEquals($expect, $actual);
    }
    
    public function testHandleContractions() {
        $string = "can't";
        $expect = 'can&apos;t';
        $actual = $this->object->handleContractions($string);
        $this->assertEquals($expect, $actual);
    }

    public function testHandleContractionsNoContraction() {
        $string = "can can";
        $expect = 'can can';
        $actual = $this->object->handleContractions($string);
        $this->assertEquals($expect, $actual);
    }

    public function testRemoveDoublePipes() {
        $string = '||';
        $expect = '|';
        $actual = $this->object->removeDoublePipes($string);
        $this->assertEquals($expect, $actual);
    }

    public function testRemoveDoublePipesMorePipes() {
        $string = '||-||';
        $expect = '|-|';
        $actual = $this->object->removeDoublePipes($string);
        $this->assertEquals($expect, $actual);
    }

    public function testHandleCompounds() {
        $string = "I|&nbsp;|do|&nbsp;|not|&nbsp;|and|&nbsp;|you|&nbsp;|do";
        $compounds = array('do-not', 'you-do');
        $expect = "|I|&nbsp;|do-not|&nbsp;|and|&nbsp;|you-do";
        $actual = $this->object->handleCompounds($string, $compounds);
        $this->assertEquals($expect, $actual);
    }
    
    public function testConvertToArray () {
        $string = 'I|do|not';
        $expect = array('I', 'do', 'not');
        $actual = $this->object->convertToArray($string);
        $this->assertEquals($expect, $actual);
    }
    
    public function testConvertToString () {
        $array = array('I', 'do', 'not');
        $expect = 'I|do|not';
        $actual = $this->object->convertToString($array);
        $this->assertEquals($expect, $actual);
    }
    
    public function testRemovePlaceholders() {
        $string = 'I&nbsp;do&comma;not&#10;have&quot;a&apos;cat&period;|';
        $expect = 'I do,not
have"a\'cat.';
        $actual = $this->object->removePlaceholders($string);
        $this->assertEquals($expect, $actual);
    }
    
    public function testGetCompoundsFromLexicon() {
        $lexicon = array (
            'a-foo'=>'afoo',
            'boo'=>'boo',
            'coo'=>'coo',
            'b-doo'=>'bdoo');
        $expect = array('a-foo', 'b-doo');
        $actual = $this->object->getCompoundsFromLexicon($lexicon);
        $this->assertEquals($expect, $actual);
    }

}
