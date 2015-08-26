<?php

namespace Bh\Page;

class Home extends Page
{
    public function renderContent()
    {
        $user = $this->controller->getCurrentUser();
        $userString = ($user) ? $user->getEmail() : 'not logged in';
        return 'this is home and you are ' . $userString;
    }
}
