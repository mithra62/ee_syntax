<?php

namespace Mithra62\Syntax\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class Filter extends AbstractRoute
{
    // Example tag: {exp:syntax:filter}
    public function process()
    {
        $data = ee('syntax:FilterService')->parse(ee()->TMPL->tagdata);
        return $data;
    }
}
