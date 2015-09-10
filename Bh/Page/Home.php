<?php

namespace Bh\Page;

class Home extends PRMPage
{
    // {{{ renderContent
    public function renderContent()
    {
        if ($this->controller->getCurrentUser()) {
            $content .= new RecordList($this->controller->getCurrentRecords(), 'record', true, false);
        }

        return parent::renderContent($content);
    }
    // }}}
}
