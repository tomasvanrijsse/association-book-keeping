<?php

namespace App\Presenters;

use Exception;

abstract class Presenter
{
    protected $model;

    protected $schemaContext = 'http://schema.org';

    /**
     * Create a new Presenter instance.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Handle dynamic property calls.
     *
     * @param  string $property
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return call_user_func([$this, $property]);
        }

        $message = '%s does not respond to the "%s" property or method.';
        throw new Exception(
            sprintf($message, static::class, $property)
        );
    }
}