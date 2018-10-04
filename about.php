<?php
/**
 * about.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */


namespace Anguish;

require_once 'app/Example.php';
require_once 'app/View.php';

$view = new View();

$data = array("About","<p>My dad used to recite Ladle Rat Rotten Hut as a part of his repertoire.</p>
<p>He died in the summer of 2016 and I did a very botched reading at his memorial service.</p>
<p>Since then, when I think of my dad, I think back on his recitations.</p>
<p>This piqued my interest in <a href=\"https://en.wikipedia.org/wiki/Anguish_Languish\">Anguish Languish</a>.</p>
<p>Nearly a year later and I'm thinking it would be fun to be able to post pithy thoughts that have been translated into Anguish Languish.</p>
<p>After looking around on the web for a few days, I came to the conclusion that a translator didn't exist.  So the translator was born.</p>
<p>I have collected a small 'canon' of examples of Anguish Languish, some by my the original author, Howard Chace.  I believe Dennis Mead has created a set of Christmas Carols. For others I don't know who the authored them. </p>
<p>From that canon I've created a dictionary of homophonic transformations that can be applied bi-directionally to translate from English to Anguish and back.</p>");
$view->setAbout($data);
echo $view->render();
exit;