<?php

namespace Unistra\ProfetesBundle\ExistDB;

use Unistra\ProfetesBundle\ExistDB\FormationCDM;

class ExistDB
{

    private $status     = 'disconnected';
    private $wsdl       = '';
    private $username   = '';
    private $password   = '';
    private $soapClient;
    private $connectionId;


    public function __construct($wsdl, $username = 'guest', $password = 'guest')
    {
        $this->wsdl = $wsdl;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getConnectionId()
    {
        return $this->connectionId;
    }

    public function getResource($id)
    {
        $path = $this->makePath($id);
        if ($path) {
            $params = array(
                'sessionId'     => $this->connectionId,
                'path'          => $path,
                'indent'        => true,
                'xinclude'      => true,
            );
            try {
                $resource = $this->soapClient->getResource($params);
            } catch (\SoapFault $e) {
                if (strstr($e->faultstring, 'not found')) {
                    throw new \Exception('Resource not found', 404);
                }
            }
        }

        $formation = new FormationCDM;
        $formation->setXML($resource->getResourceReturn);

        return $formation;
    }

    public function getXQuery($xquery, $start = 1, $howmany = 1000)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

        $queryParams = array(
            'sessionId'     => $this->connectionId,
            'xpath'         => $xquery,
        );
        $query = $this->soapClient->query($queryParams);

        $retrieveParams = array(
            'sessionId'     => $this->connectionId,
            'start'         => $start,
            'howmany'       => $howmany,
            'indent'        => true,
            'xinclude'      => true,
            'highlight'     => true,
        );
        $result = $this->soapClient->retrieve($retrieveParams)->retrieveReturn;

        if ($result) {
            if (is_array($result)) {
                $xml .= implode("\n", $result) . "\n";
            } else {
                $xml .= $result . "\n";
            }
        }
        return $xml;
    }

    public function loadXQueryFromFile($xqueryFile, $params = null)
    {
        if (is_file($xqueryFile) && is_readable($xqueryFile)) {
            $xquery = file_get_contents($xqueryFile);
        } else {
            throw new \Exception(sprintf('No such XQuery file %s', $xqueryFile));
        }

        if ($params && is_array($params)) {
            foreach ($params as $paramNumber => $paramValue) {
                $xquery = str_replace('{{{' . $paramNumber . '}}}', $paramValue, $xquery);
            }
        }

        return $xquery;
    }

    public function getPrettyId($id)
    {
        return str_replace('_', '-', strtolower($id));
    }

    public function getOriginalId($id)
    {
        return str_replace('-', '_', strtoupper($id));
    }

    protected function connect()
    {
        if ($this->wsdl) {
            $credentials = array(
                'userId'    => $this->username,
                'password'  => $this->password);
            $this->soapClient = new \SoapClient($this->wsdl);
            $this->connectionId = $this->soapClient->connect($credentials)->connectReturn;
            $this->status = 'connected';
        }
    }

    protected function makePath($id)
    {
        $pattern = '/^fr-rne-06\d{5}[a-z]-pr-\w+-\w+$/';
        if (preg_match($pattern, $id)) {
            $idparts = explode('-', strtoupper($id));
            #format: /db/CDM/WSDiplomeCDM-0673021V-FRAN-PS103-202.xml pour
            #fr-rne-0673021v-pr-ps103-202
            return sprintf('%s/WSDiplomeCDM-%s-FRAN-%s-%s.xml',
                '/db/CDM-2009',
                $idparts[2],
                $idparts[4],
                $idparts[5]
            );
        } else {
            throw new \Exception(sprintf('Resource %s does not exist', $id), 404);
        }
    }
}

