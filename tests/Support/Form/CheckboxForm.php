<?php

declare(strict_types=1);

namespace Yiisoft\Form\Tests\Support\Form;

use stdClass;
use Yiisoft\Form\FormModel;

final class CheckboxForm extends FormModel
{
    private bool $red = true;
    private bool $blue = false;
    private int $age = 42;
    private stdClass $object;

    public function __construct()
    {
        parent::__construct();
        $this->object = new stdClass();
    }

    public function getAttributeLabels(): array
    {
        return [
            'red' => 'Red color',
            'blue' => 'Blue color',
            'age' => 'Your age 42?',
        ];
    }

    public function getAttributeHints(): array
    {
        return [
            'red' => 'If need red color.',
        ];
    }
}
