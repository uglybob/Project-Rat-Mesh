<?php

namespace Bh\Page;

class Home extends PRMPage
{
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Project Rat Mesh';
    }
    // }}}
    // {{{ renderContent
    public function renderContent()
    {
        $user = $this->controller->getCurrentUser();
        $userString = ($user) ? $user->getEmail() : 'not logged in';

        if ($this->controller->getCurrentUser()) {
            $content .= new RecordList($this->controller->getCurrentRecords(), 'record', true, false);
        }

        return parent::renderContent($content);
    }
    // }}}
}
