<?php
/**
 * View.php
 *
 * @version $Id: $
 * @package VirtualInvite
 * @copyright 2011-2016
 */

namespace Anguish;


class View
{

    protected $results = '';
    protected $form = '';

    public function __construct()
    {

    }

    public function render()
    {
        $html = file_get_contents('./templates/layout.html');
        $html = str_replace('@@results@@', $this->results, $html);
        $html = str_replace('@@form@@', $this->form, $html);
        return $html;
    }

    public function setResults($data) {
        $html = file_get_contents('./templates/results.html');
        $html = str_replace(array('@@orig@@', '@@translated@@'), $data, $html);
        $this->results = $html;
    }

    public function setExample($data) {
        $html = file_get_contents('./templates/example.html');
        $html = str_replace(array('@@title@@','@@example@@'), $data, $html);
        $this->results = $html;
    }

    public function setTabbedExample($data) {
        $html = file_get_contents('./templates/tabbed-example.html');
        $html = str_replace(array('@@title@@','@@anguish@@', '@@english@@'), $data, $html);
        $this->results = $html;
    }

    public function setAbout($data) {
        $html = file_get_contents('./templates/about.html');
        $html = str_replace(array('@@title@@','@@about@@'), $data, $html);
        $this->results = $html;
    }

    public function loadForm() {
        $html = file_get_contents('./templates/form.html');
        $this->form = $html;
    }
}