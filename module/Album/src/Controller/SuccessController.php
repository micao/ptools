<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController extends AbstractActionController
{
    private $authService;

    public function __construct(
        \Zend\Authentication\AuthenticationService $authService
    ) {
        $this->authService = $authService;
    }
    
    public function indexAction()
    {
        if (! $this->authService->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }
}
