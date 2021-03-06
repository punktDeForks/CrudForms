<?php

namespace Sandstorm\CrudForms\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\Exception;
use Sandstorm\CrudForms\Exception\MissingModelTypeException;

class FormDefinitionViewHelper extends AbstractDefinitionViewHelper
{

    /**
     * @return void
     * @throws Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument('object', 'object', 'object', true);
        $this->registerArgument('model', 'string', 'the model class name', true);
        $this->registerArgument('context', 'object', 'n arbitrary object which is available in all actions and nested functionality', false, null);
    }

    /**
     * @return array
     * @throws MissingModelTypeException
     */
    public function render()
    {
        $object = $this->arguments['object'];
        $model = $this->arguments['model'];
        $context = $this->arguments['context'];

        $fields = $this->getProperties($model, $context);

        $fields = array_filter($fields, static function ($element) {
            return $element['visible'] && $element['visibleInForm'];
        });

        return [
            'object' => $object,
            'fields' => $fields,
            'context' => $context
        ];
    }
}
