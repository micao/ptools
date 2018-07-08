<?php

namespace Album\Controller;
/*
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
*/
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;

use Album\Model\User\User;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authService;
    protected $redirect;
    
    public function __construct(
        \Zend\Authentication\AuthenticationService $authService,
        \Album\Model\MyAuthStorage $storage
    ) {
        $this->authService = $authService;
        $this->storage     = $storage;
    }

    public function getAuthService()
    {
        //echo get_class($this->authService);die;
        return $this->authService;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('Album\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function getForm()
    {
        if (! $this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            error_log("redirect to album");
            $this->redirect()->toRoute('album');
        }

        $form       = $this->getForm();

        return array(
            'form'      => $form,
            'messages'  => null//$this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form       = $this->getForm();
        $this->redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('login'))
                                       ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();

                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    //$this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $this->redirect = 'album';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($request->getPost('login'));
                }
            }
        }
        return $this->redirect()->toRoute($this->redirect);;
    }

    public function logoutAction()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $this->getSessionStorage()->forgetMe();
            $this->getAuthService()->clearIdentity();
            //$this->flashmessenger()->addMessage("You've been logged out");
        }

        return $this->redirect()->toRoute('login');
    }
}
