<?php

namespace Album\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Album
{
    public  $id;

    public  $artist;

    public  $title;

    public $id_user;

    public $work_type;

    public $num;

    public $id_category;

    public $category;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
        $this->title  = (!empty($data['title'])) ? $data['title'] : null;
        $this->id_user = (!empty($data['id_user'])) ? $data['id_user'] : null;
        $this->work_type  = (!empty($data['work_type'])) ? $data['work_type'] : null;
        $this->num  = (!empty($data['num'])) ? $data['num'] : null;
        $this->id_category  = (!empty($data['id_category'])) ? $data['id_category'] : null;
        $this->category  = (!empty($data['category'])) ? $data['category'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'        => $this->id,
            'artist'    => $this->artist,
            'title'     => $this->title,
            'id_user'   => $this->id_user,
            'work_type' => $this->work_type,
            'num'           => $this->num,
            'id_category'   => $this->id_category,
            'category'   => $this->category,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name'     => 'id',
            'required' => true,
            'filters'  => [
                ['name' => 'int'],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'artist',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'       => 'title',
            'required'   => true,
            'filters'    => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
            ],
        ]);
/*
        $inputFilter->add([
            'name'     => 'num',
            'required' => true,
            'filters'  => [
                ['name' => 'int'],
            ],
        ]);
*/
        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}
