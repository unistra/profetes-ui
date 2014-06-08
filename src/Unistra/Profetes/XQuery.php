<?php

namespace Unistra\Profetes;

class XQuery
{
    private $xquery;

    private $parameters;

    public function __construct($xquery)
    {
        $this->xquery = $xquery;
        $this->parameters = array();
    }

    public function getXQuery()
    {
        return $this->applyParameters();
    }

    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge(
            $this->parameters,
            $parameters
        );
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    private function applyParameters()
    {
        $xquery = $this->xquery;
        foreach ($this->parameters as $paramName => $paramValue) {
            $paramValue = $this->escapeParameterValue($paramValue);
            $placeholder = '{{{' . $paramName . '}}}';
            $xquery = str_replace($placeholder, $paramValue, $xquery);
        }

        return $xquery;
    }

    /**
     * @param string $paramValue value to escape
     * @return string
     */
    private function escapeParameterValue($paramValue)
    {
        $paramValue = str_replace('"', '&#34;', $paramValue);
        $paramValue = str_replace("'", '&#39;', $paramValue);

        return $paramValue;
    }
}
