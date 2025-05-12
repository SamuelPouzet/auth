<?php

namespace SamuelPouzet\Auth\Form;

use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\Validator\Hostname;
use SamuelPouzet\Auth\Interface\Form\UserFormInterface;

class UserForm extends Form implements InputFilterAwareInterface, UserFormInterface
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
            'name' => 'login',
            'type' => Text::class,
            'options' => [
                'label' => 'Votre login',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => Password::class,
            'options' => [
                'label' => 'Votre mot de passe',
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => Email::class,
            'options' => [
                'label' => 'Votre email',
            ],
        ]);
    }

    protected function addInputFilters(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
                'name'     => 'login',
                'required' => true,
                'filters'  => [
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

        $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [
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

        $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => Hostname::ALLOW_DNS,
                            'useMxCheck' => false,
                        ],
                    ],
                ],
            ]);
    }
}
