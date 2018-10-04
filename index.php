<?php
/**
 * index.php
 *
 * @version $Id: $
 * @package Anguish
 * @copyright 2018
 */


namespace Anguish;

// if I have a post, translate the text and add a section to the page showing the original text and the new text
// if I don't have a post show the form

require_once 'app/Parser.php';
require_once 'app/View.php';
require_once 'dictionaries/lexicon.php';

$view = new View();
if ((isset($_POST['debug']) && ($_POST['debug'] === 'on'))) {
    $debug = true;
} else {
    $debug = false;
}
$post = $_POST;
$messages = false;
if (isPost($post)) {
//    var_dump($post); exit;
    // do the translation and add the results to the view
    $parser = new Parser();
    $parser->setDebug($debug);

    if (isset($post['language']) && $post['language'] == 'anguish') {
        $lexicon = $englishToAnguish;
    } else {
        $lexicon = $anguishToEnglish;
    }
    $translated = $parser->translate($post['text'], $lexicon);

    $data = array(htmlspecialchars($post['text']),htmlspecialchars($translated));
    $view->setResults($data);
    $messages = $parser->getDebugMessages();
}
$view->loadForm();
echo $view->render();

if(!empty($messages)) {
    var_dump($messages);
}

exit;

function isPost($post) {
    if (array_key_exists('language', $post) && (array_key_exists('text', $post))) {
        return true;
    }
    return false;
}