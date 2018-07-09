<?php

namespace Album\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class AlbumForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('加工工件');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name'    => 'title',
            'type'    => 'text',
            'options' => [
                'label' => '备注',
            ],
        ]);
        $this->add([
            'name'    => 'artist',
            'type'    => 'text',
            'options' => [
                'label' => '员工',
                'enabled' => false,
            ],
        ]);

        $this->add([
            'name'    => 'id_user',
            'type'    => 'hidden',
        ]);

        $select = new Element\Select('id_category');
        $select->setLabel('零件类别');
        $select->setValueOptions(array(
            '1' => '垫片',
            '2' => '销轴',
            '3' => '碟簧',
            '4' => '波簧',
        ));
        $this->add($select);

        $this->add([
            'name'    => 'num',
            'type'    => 'number',
            'options' => [
                'label' => '加工数量',
            ],
        ]);

        $select = new Element\Select('id_work_type');
        $select->setLabel('加工类别');
        $select->setValueOptions(array(
            '1' => '加工',
            '2' => '返工',
        ));
        $this->add($select);

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
