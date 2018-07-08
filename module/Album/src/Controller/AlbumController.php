<?php

namespace Album\Controller;

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Model\User\UserTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $table;
    private $userTable;
    private $authService;

    public function __construct(AlbumTable $table, UserTable $userTable,
                                \Zend\Authentication\AuthenticationService $authService)
    {
        $this->table = $table;
        $this->userTable = $userTable;
        $this->authService = $authService;
    }

    /**
     * @return AlbumTable
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param AlbumTable $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return UserTable
     */
    public function getUserTable()
    {
        return $this->userTable;
    }

    /**
     * @param UserTable $userTable
     */
    public function setUserTable($userTable)
    {
        $this->userTable = $userTable;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param \Zend\Authentication\AuthenticationService $authService
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    public function indexAction()
    {
        if(!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('login');
        }
        $login = $this->getAuthService()->getIdentity();

        var_dump($login);
        // Grab the paginator from the AlbumTable:
        $paginator = $this->table->fetchAll(true, $login);

        //$userObj = $this->userTable->find($paginator->id_user);

        //var_dump($paginator);

/*
        foreach ($paginator as &$item) {
            $userObj = $this->userTable->find($item->id_user);
            $item->artist = '$userObj->getName()';
            //var_dump($item);
        }
*/
//var_dump($paginator);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(10);

        return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);

        return $this->redirect()->toRoute('album');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveAlbum($album);

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return [
            'id'    => $id,
            'album' => $this->table->getAlbum($id),
        ];
    }
}
