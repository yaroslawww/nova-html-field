<?php

namespace ThinkStudio\HtmlField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Html extends Field
{
    public $component      = 'html-field';
    public $showOnIndex    = false;
    public $showOnCreation = false;
    public $showOnUpdate   = false;
    public $showOnPreview  = true;


    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->attribute = 'TemporaryNotComputedField';
    }


    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $this->attribute = 'ComputedField';
        parent::resolveForDisplay($resource, $attribute);
        $this->attribute = 'ComputedField';
    }

    /**
     * Override attribute name
     * @see ResolvesFields::removeNonUpdateFields
     * @inheritDoc
     */
    public function resolve($resource, $attribute = null)
    {
        $this->attribute = 'ComputedField';
        parent::resolve($resource, $attribute);
        $this->attribute = 'TemporaryNotComputedField';
    }

    public function fill(NovaRequest $request, $model)
    {
        // nothing
    }

    public function jsonSerialize(): array
    {
        // AD-hoc for using filed in actions.
        if (!$this->value && $this->attribute == 'TemporaryNotComputedField') {
            $this->resolve($this->resource);
        }

        return array_merge(parent::jsonSerialize(), [
            'asHtml' => true,
            'value'  => $this->value,
        ]);
    }
}
