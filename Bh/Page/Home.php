<?php

namespace Bh\Page;

class Home extends PRMPage
{
    public function renderContent()
    {
        $user = $this->controller->getCurrentUser();
        $userString = ($user) ? $user->getEmail() : 'not logged in';

        $content = '<div>this is home and you are ' . $userString . '</div>';

        if ($this->controller->getCurrentUser()) {
            $content .= new ObjectList($this->controller->getCurrentRecords(), 'record');
        }

        return parent::renderContent($content);
    }
}
