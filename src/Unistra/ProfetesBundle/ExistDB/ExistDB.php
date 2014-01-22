<?php

namespace Unistra\ProfetesBundle\ExistDB;

use Unistra\ProfetesBundle\ExistDB\FormationCDM;

class ExistDB
{

    const   CONNECTED = 1;
    const   DISCONNECTED = 0;
    const   FICHE = 'f';
    const   XQUERY = 'q';

    private $status     = self::DISCONNECTED;
    private $wsdl       = '';
    private $username   = '';
    private $password   = '';
    private $soapClient;
    private $connectionId;
    private $cacheDirName;
    private $cacheMaxAge;


    public function __construct($wsdl, $username = 'guest', $password = 'guest', $options = null)
    {
        $this->wsdl = $wsdl;
        $this->username = $username;
        $this->password = $password;

        $this->setCacheMaxAge(60 * 60 * 24 * 7); # 7 days

        if (is_array($options) && count($options)) {
            foreach ($options as $optionName => $optionValue) {
                $callable = array($this, 'set' . $optionName);
                if (is_callable($callable)) {
                    call_user_func_array(array($this, 'set' . $optionName), array($optionValue));
                }
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getConnectionId()
    {
        return $this->connectionId;
    }

    /**
     * Récupère une fiche diplome de la base eXist à partir de son id
     *
     * @param string $id id de la fiche à retourner
     *
     * @return string la fiche au format XML CDM-fr
     */
    public function getResource($id)
    {

        $resource = $this->loadXQueryFromCache($id, ExistDB::FICHE);

        if (!$resource) {
            $path = $this->makePath($id);

            if ($path) {
                $this->connect();
                $params = array(
                    'sessionId'     => $this->getConnectionId(),
                    'path'          => $path,
                    'indent'        => true,
                    'xinclude'      => true,
                );
                try {
                    $resource = $this->soapClient->getResource($params);
                    $resource = $resource->getResourceReturn;
                    $this->saveXQueryToCache($id, $resource, ExistDB::FICHE);
                } catch (\SoapFault $e) {
                    if (strstr($e->faultstring, 'not found')) {
                        throw new \Exception('Resource not found', 404);
                    }
                }
            }
        }

        $formation = new FormationCDM;
        $formation->setXML($resource);

        return $formation;
    }

    /**
     * Execute une requête XQuery dans la base eXist et retourne le résultat
     *
     * @param string $xquery la requête XQuery
     * @param int $start offset du premier résultat
     * @param int $howmany nombre max de résultats à retourner
     *
     * @return string résultat retourné par la base eXist
     */
    public function getXQuery($xquery, $options = array())
    {
        $withXmlProlog = (array_key_exists('withXmlProlog', $options) ? $options['withXmlProlog'] : true);
        $useCache = (array_key_exists('useCache', $options) ? $options['useCache'] : true);
        $xml = '';

        if ($useCache && $cacheContent = $this->loadXQueryFromCache($xquery, ExistDB::XQUERY)) {
            return $xml . $cacheContent;
        }

        $this->connect();
        $queryParams = array(
            'sessionId'     => $this->getConnectionId(),
            'xpath'         => $xquery,
        );
        $query = $this->soapClient->query($queryParams);

        $retrieveParams = array(
            'sessionId'     => $this->connectionId,
            'start'         => (array_key_exists('start', $options) ? $options['start'] : 1),
            'howmany'       => (array_key_exists('howmany', $options) ? $options['howmany'] : 1000),
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
            if ($useCache) {
                $this->saveXQueryToCache($xquery, $result, ExistDB::XQUERY);
            }
            if ($withXmlProlog) {
                return '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $xml;
            } else {
                return $xml;
            }
        }

        return '';
    }

    /**
     * Charge une requête XQuery à partir d'un fichier sur le disque
     *
     * Le fichier peut contenir des placeholders qui seront remplacés par des
     * paramètres passés sous forme de tableau dans $params.
     * Ces placeholders sont sous la forme {{{param}}} où param est la clé
     * de l'entrée du tableau.
     *
     * @param string $xqueryFile Fichier xquery à charger
     * @param array|null $params tableau de paramètres à remplacer dans le xquery
     *
     * @return string|null la requête xquery
     */
    public function loadXQueryFromFile($xqueryFile, $params = null)
    {
        if (is_file($xqueryFile) && is_readable($xqueryFile)) {
            $xquery = file_get_contents($xqueryFile);
        } else {
            throw new \Exception(sprintf('No such XQuery file %s', $xqueryFile));
        }

        if ($params && is_array($params)) {
            foreach ($params as $paramName => $paramValue) {
                $paramValue = str_replace("'", "''", $paramValue);
                $xquery = str_replace('{{{' . $paramName . '}}}', $paramValue, $xquery);
            }
        }

        return $xquery;
    }

    /**
     * Convertit les Id du format CDM au format d'affichage
     *
     * FR_RNE_0673021V_PR_LV103_204 => fr-rne-0673021v-pr-lv103-204
     */
    public function getPrettyId($id)
    {
        return str_replace('_', '-', strtolower($id));
    }

    /**
     * Convertit les Id du format d'affichage au format CDM
     *
     * fr-rne-0673021v-pr-lv103-204 => FR_RNE_0673021V_PR_LV103_204
     */
    public function getOriginalId($id)
    {
        return str_replace('-', '_', strtoupper($id));
    }

    public function getCacheDir()
    {
        return $this->cacheDirName;
    }

    public function setCacheDir($cacheDir)
    {
        if (substr($cacheDir, -1) == '/') {
            $cacheDir = substr($cacheDir, 0, -1);
        }
        if (is_dir($cacheDir) && is_readable($cacheDir) && is_writable($cacheDir))
        {
            $this->cacheDirName = $cacheDir;
        } else if (mkdir($cacheDir, 0777, true)) {
            $this->cacheDirName = $cacheDir;
        } else {
            throw new \Exception(sprintf('%s is not a valid cache directory', $cacheDir));
        }
    }

    public function setCacheMaxAge($maxAge) {
        $this->cacheMaxAge = (int)$maxAge;
    }

    protected function connect()
    {
        if (self::CONNECTED == $this->getStatus()) {
            return $this->getConnectionId();
        } else if ($this->wsdl) {
            $credentials = array(
                'userId'    => $this->username,
                'password'  => $this->password);
            $this->soapClient = new \SoapClient($this->wsdl);
            $this->connectionId = $this->soapClient->connect($credentials)->connectReturn;
            $this->status = self::CONNECTED;

            return $this->getConnectionId();
        } else {
            throw new \Exception(sprintf('%s is not a valid wsdl', $this->wsdl));
        }
    }

    /**
     * Détermine le chemin d'une ressource à partir de son ID
     *
     * fr-rne-0673021v-pr-lv103-204 =>
     * /db/CDM/WSDiplomeCDM-0673021V-FRAN-LV103-204
     */
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

    /**
     * Détermine si une version en cache peut être utilisée
     *
     */
    protected function loadXQueryFromCache($xquery, $typeOfQuery = ExistDB::FICHE)
    {
        $fileName = md5($xquery);
        $fileName = sprintf('%s/%s/%s/%s', $this->getCacheDir(), $typeOfQuery, substr($fileName, 0, 1), substr($fileName, 1));
        if (is_file($fileName) && is_readable($fileName)) {
            if ((time() - filemtime($fileName)) < $this->cacheMaxAge) {
                $cachedQuery = file_get_contents($fileName);

                return $cachedQuery;
            }
        }

        return false;
    }

    /**
     * Enregistre le résultat de la requête en cache
     *
     */
    protected function saveXQueryToCache($xquery, $queryResult, $typeOfQuery = ExistDB::FICHE)
    {
        $fileName = md5($xquery);
        $fileName = sprintf('%s/%s/%s/%s', $this->getCacheDir(), $typeOfQuery, substr($fileName, 0, 1), substr($fileName, 1));
        $dirName = dirname($fileName);
        if (!is_dir($dirName)) {
            mkdir($dirName, 0777, true);
        }
        file_put_contents($fileName, $queryResult);
    }
}

