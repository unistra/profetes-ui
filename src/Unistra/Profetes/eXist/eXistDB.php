<?php

/*
 * Copyright Université de Strasbourg (2015)
 *
 * Daniel Bessey <daniel.bessey@unistra.fr>
 *
 * This software is a computer program whose purpose is to diplay course information
 * extracted from a Profetes database on a website.
 *
 * See LICENSE for more details
 */

namespace Unistra\Profetes\eXist;

class eXistDB
{
    private $soapClient;

    private $username;

    private $password;

    private $collection;

    private $codeRne;

    private $connectionId;

    /**
     * @param \SoapClient $soapClient PHP SoapClient
     * @param string      $username   eXist-db server username
     * @param string      $password   eXist-db server password
     * @param string      $collection the eXist-db collection name
     * @param string      $codeRne
     */
    public function __construct(\SoapClient $soapClient, $username, $password, $collection, $codeRne)
    {
        $this->soapClient = $soapClient;
        $this->username = $username;
        $this->password = $password;
        $this->collection = $collection;
        $this->codeRne = $codeRne;
    }

    /**
     * Fetch a XML document from the eXist-db database using its resource path
     *
     * @param  string     $path path of the resource to fetch
     * @return string     XML of the resource document
     * @throws \Exception with code 404 if resource not found
     */
    public function getResource($path)
    {
        $path = $this->replaceCollection($path);

        $this->connect();
        $params = array(
            'sessionId' => $this->connectionId,
            'path' => $path,
            'indent' => true,
            'xinclude' => true,
        );

        try {
            $resource = $this->soapClient
                ->getResource($params)
                ->getResourceReturn;
        } catch (\SoapFault $fault) {
            if (strstr($fault->faultstring, 'not found')) {
                throw new \Exception(
                    sprintf("Resource %s not found\n%s", $path, $fault->faultstring),
                    404);
            } else {
                throw new \Exception($fault->faultstring);
            }
        }

        return $resource;
    }

    /**
     * Execute an XQuery against the database
     *
     * @param  string     $xquery       the XQuery to execute
     * @param  boolean    $addXmlProlog wether to add the XML prolog to the result
     * @return string
     * @throws \Exception
     */
    public function xquery($xquery, $addXmlProlog = true)
    {
        $xquery = $this->replaceCollection($xquery);
        $xquery = $this->replaceCodeRne($xquery);

        $xml = '';
        if ($addXmlProlog) {
            $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
        }

        $this->connect();

        try {
            $params = array(
                'sessionId' => $this->connectionId,
                'xpath' => $xquery,
            );
            $query = $this->soapClient->query($params);

            $params = array(
                'sessionId' => $this->connectionId,
                'start' => 1,
                'howmany' => $query->queryReturn->hits,
                'indent' => true,
                'xinclude' => true,
                'highlight' => true,
            );
            $result = $this->soapClient->retrieve($params)->retrieveReturn;

            if ($result) {
                if (is_array($result)) {
                    $xml .= implode("\n", $result)."\n";
                } else {
                    $xml .= $result."\n";
                }

                return $xml;
            }
        } catch (\SoapFault $fault) {
            throw new \Exception($fault->faultstring);
        }
    }

    private function connect()
    {
        if ($this->connectionId) {
            return;
        }

        $credentials = array(
            'userId' => $this->username,
            'password' => $this->password,
        );

        try {
            $this->connectionId = $this->soapClient
                ->connect($credentials)
                ->connectReturn;
        } catch (\Exception $e) {
            throw new \Exception("Unable to connect to soap server\n".$e->getMessage());
        }
    }

    /**
     * @param $path
     * @return mixed
     */
    private function replaceCollection($path)
    {
        $path = str_replace('%collection%', $this->collection, $path);

        return $path;
    }

    private function replaceCodeRne($xquery)
    {
        $xquery = str_replace('%code_rne%', $this->codeRne, $xquery);

        return $xquery;
    }
}
