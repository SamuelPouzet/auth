<?php

namespace SamuelPouzet\Auth\Form;

use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use SamuelPouzet\Auth\Interface\Form\TokenFormInterface;

class TokenForm extends Form implements TokenFormInterface
{
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->addFormElements();
        $this->addInputFilters();
    }

    protected function addFormElements(): void
    {
        $this->add([
            'name' => 'token',
            'type' => Text::class,
        ]);
    }

    protected function addInputFilters(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name' => 'token',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);
    }
}
