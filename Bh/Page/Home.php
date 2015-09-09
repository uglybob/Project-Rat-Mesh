<?php

namespace Bh\Page;

class Home extends PRMPage
{
    public function renderContent()
    {
        $user = $this->controller->getCurrentUser();
        $userString = ($user) ? $user->getEmail() : 'not logged in';

        $content = HTML::div("this is home and you are $userString");

        if ($this->controller->getCurrentUser()) {
            $content .= new RecordList($this->controller->getCurrentRecords(), 'record', true, false);
        }

        return parent::renderContent($content);
    }
}
