<?php
namespace App\Core;

class View
{
    function generate($content_view, $template_view, $bootstrap,$data = null)
    {
        include_once 'app/views/'.$template_view;
    }
}