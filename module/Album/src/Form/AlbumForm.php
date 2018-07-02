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

        $select = new Element\Select('category');
        $select->setLabel('零件类别');
        $select->setValueOptions(array(
            '0' => '垫片',
            '1' => '销轴',
            '2' => '碟簧',
            '3' => '波簧',
        ));
        $this->add($select);

        $this->add([
            'name'    => 'num',
            'type'    => 'number',
            'options' => [
                'label' => '加工数量',
            ],
        ]);

        $select = new Element\Select('type');
        $select->setLabel('加工类别');
        $select->setValueOptions(array(
            '0' => '加工',
            '1' => '返工',
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
