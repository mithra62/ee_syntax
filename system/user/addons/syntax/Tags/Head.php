<?php

namespace Mithra62\Syntax\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class Head extends AbstractRoute
{
    // Example tag: {exp:syntax:head}
    public function process()
    {
        $theme_folder_url = ee()->config->item('theme_folder_url');
        if (substr($theme_folder_url, -1) != '/') {
            $theme_folder_url .= '/';
        }

        $css_url = $theme_folder_url . "user/syntax/styles/ee-syntax.css";
        return "\n" . '<link rel="stylesheet" href="' . $css_url . '" type="text/css" media="screen" />' . "\n";
    }
}
