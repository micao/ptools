<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;

use Album\Model\User\User;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authService;
    
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
            return $this->redirect()->toRoute('success');
        }

        $form       = $this->getForm();

        return array(
            'form'      => $form,
            'messages'  => null//$this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        var_dump($this->getRequest()->isPost());
        $form       = $this->getForm();
        $redirect = 'login';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            var_dump($request->getPost());
            if ($form->isValid()) {
                var_dump("is validated data");
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('login'))
                                       ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                var_dump($result->isValid());

                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    //$this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {

                    $redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                }
            }
        }

        return $this->redirect()->toRoute($redirect);
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
