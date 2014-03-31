<?php

namespace BukkaAccounts\Model;

use NumberFormatter;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Income implements InputFilterAwareInterface
{
    protected $id;
    protected $inputFilter;
    
    protected $params = array(
        'income_date' => '0000-00-00',
        'price' => 0,
        'invoice_id' => "",
        'description' => "",
    );
    
    public function __get($name)
    {
        if ($name == 'id' || $name == 'income_id') {
            return $this->id;
        }
        if (!array_key_exists($name, $this->params)) {
            throw new Exception("Invalid income item requested");
        }
        return $this->params[$name];
    }
    
    public function exchangeArray(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        } else {
            $this->id = isset($data['income_id']) ? $data['income_id'] : null;
        }
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->params)) {
                $this->params[$key] = $value;
            }
        }
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getArrayCopy()
    {
        $params = $this->getParams();
        $params['id'] = $this->id;
        return $params;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'price',
                'required' => true,
                'filters'  => array(
                    array(
                        'name' => 'NumberFormat',
                        'options' => array(
                            'locale' => 'en_GB',
                            'style'  => NumberFormatter::DEFAULT_STYLE,
                            'type'   => NumberFormatter::TYPE_CURRENCY,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'invoice_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 20,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'description',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 200,
                        ),
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception("Not implemented");
    }

}
