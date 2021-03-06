<?php namespace Lego\Foundation\Concerns;

use Illuminate\Support\MessageBag;

trait MessageOperator
{
    /**
     * 所有提示信息
     * @var MessageBag
     */
    private $messages;

    /**
     * 所有错误信息
     * @var MessageBag
     */
    private $errors;

    protected function initializeMessageOperator()
    {
        $this->messages = new MessageBag();
        $this->errors = new MessageBag();
    }

    public function messages()
    {
        return $this->messages;
    }

    public function errors()
    {
        return $this->errors;
    }
}
