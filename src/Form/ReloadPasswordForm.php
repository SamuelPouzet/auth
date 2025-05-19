<?php

namespace SamuelPouzet\Auth\Form;

use Laminas\Form\Element\Password;
use Laminas\Form\Form;
use SamuelPouzet\Auth\Interface\Form\ReloadPasswordFormInterface;

class ReloadPasswordForm extends Form implements ReloadPasswordFormInterface
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
            'type'  => Password::class,
            'name' => 'new_password',
            'options' => [
                'label' => 'New Password',
            ],
        ]);

        $this->add([
            'type'  => Password::class,
            'name' => 'confirm_new_password',
            'options' => [
                'label' => 'Confirm new password',
            ],
        ]);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Sign in',
                'id' => 'submit',
            ],
        ]);
    }

    protected function addInputFilters(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name'     => 'new_password',
            'required' => true,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'confirm_new_password',
            'required' => true,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'new_password',
                    ],
                ],
            ],
        ]);
    }
}
