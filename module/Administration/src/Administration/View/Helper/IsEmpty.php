<?php

namespace Administration\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IsEmpty extends AbstractHelper
{
    public function __invoke($text, $placeholder = 'empty')
    {
        if ($text === '') {
            return '<em>' . $placeholder . '</em>';
        }
        return $text;
    }
}
