<?php

namespace Unistra\ProfetesBundle\ExistDB;

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Accès à la base de donnée eXist Profetes
 *
 * Permet de se connecter à la base, d'en extraire une fiche et d'effectuer des
 * requêtes XQuery
 */
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
    private $stopwatch = null;
    private $logger = null;

    public function __construct($wsdl, $username = 'guest', $password = 'guest', $options = null, Stopwatch $stopwatch = null, $logger = null)
    {
        $this->wsdl = $wsdl;
        $this->username = $username;
        $this->password = $password;
        $this->stopwatch = $stopwatch;
        $this->logger = $logger;

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

    /**
     * Retourne le statut de la connexion
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Retourne l'id de connection
     *
     * @return integer connectionId
     */
    public function getConnectionId()
    {
        return $this->connectionId;
    }

    /**
     * Récupère une fiche diplome de la base eXist à partir de son id
     *
     * @param string $id id de la fiche à retourner
     *
     * @return FormationCDM la fiche au format XML CDM-fr
     */
    public function getResource($id)
    {

        $resource = $this->loadXQueryResultFromCache($id, ExistDB::FICHE);

        if (!$resource) {
            $path = $this->makePath($id);

            if ($path) {
                if ($this->stopwatch) {
                    $this->stopwatch->start('ExistDB::getResource');
                }
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
                if ($this->stopwatch) {
                    $this->stopwatch->stop('ExistDB::getResource');
                }
                if ($this->logger) {
                    $this->logger->debug('getResource: ' . $id);
                }
            }
        }

        $formation = new FormationCDM();
        $formation->setXML($resource);

        return $formation;
    }

    /**
     * Execute une requête XQuery dans la base eXist et retourne le résultat
     *
     * @param string $xquery  la requête XQuery
     * @param array  $options options à la requête
     *
     * @return string résultat retourné par la base eXist
     */
    public function getXQuery($xquery, $options = array())
    {
        $withXmlProlog = (array_key_exists('withXmlProlog', $options) ? $options['withXmlProlog'] : true);
        $useCache = (array_key_exists('useCache', $options) ? $options['useCache'] : true);
        $xml = '';

        if ($useCache && $cacheContent = $this->loadXQueryResultFromCache($xquery, ExistDB::XQUERY)) {
            return $xml . $cacheContent;
        }

        if ($this->stopwatch) {
            $this->stopwatch->start('ExistDB::getXQuery');
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
            'howmany'       => (array_key_exists('howmany', $options) ? $options['howmany'] : $query->queryReturn->hits),
            'indent'        => true,
            'xinclude'      => true,
            'highlight'     => true,
        );
        $result = $this->soapClient->retrieve($retrieveParams)->retrieveReturn;
        if ($this->stopwatch) {
            $this->stopwatch->stop('ExistDB::getXQuery');
        }
        if ($this->logger) {
            $this->logger->debug('getXQuery: ' . $xquery);
        }

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
     * @param array  $params     tableau de paramètres à remplacer dans le xquery
     *
     * @return string le résultat de la requête xquery
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
        if ($this->logger) {
            $this->logger->debug('loadXQueryFromFile: ' . $xquery);
        }

        return $xquery;
    }

    /**
     * Convertit les Id du format CDM au format d'affichage
     *
     * FR\_RNE\_0673021V\_PR\_LV103\_204 => fr-rne-0673021v-pr-lv103-204
     */
    public function getPrettyId($id)
    {
        return str_replace('_', '-', strtolower($id));
    }

    /**
     * Convertit les Id du format d'affichage au format CDM
     *
     * fr-rne-0673021v-pr-lv103-204 => FR\_RNE\_0673021V\_PR\_LV103\_204
     */
    public function getOriginalId($id)
    {
        return str_replace('-', '_', strtoupper($id));
    }

    public function getCacheDir()
    {
        return $this->cacheDirName;
    }

    /**
     * Définit le répertoire utilisé pour stocker le cache
     *
     * @param  string $cacheDir le répertoire du cache
     * @return void
     */
    public function setCacheDir($cacheDir)
    {
        if (substr($cacheDir, -1) == '/') {
            $cacheDir = substr($cacheDir, 0, -1);
        }
        if (is_dir($cacheDir) && is_readable($cacheDir) && is_writable($cacheDir)) {
            $this->cacheDirName = $cacheDir;
        } elseif (mkdir($cacheDir, 0777, true)) {
            $this->cacheDirName = $cacheDir;
        } else {
            throw new \Exception(sprintf('%s is not a valid cache directory', $cacheDir));
        }
    }

    /**
     * Définit la durée pendant laquelle le cache est valable
     *
     * @param  int  $maxAge durée de validité en secondes
     * @return void
     */
    public function setCacheMaxAge($maxAge)
    {
        $this->cacheMaxAge = (int) $maxAge;
    }

    protected function connect()
    {
        if (self::CONNECTED == $this->getStatus()) {
            return $this->getConnectionId();
        } elseif ($this->wsdl) {
            $credentials = array(
                'userId'    => $this->username,
                'password'  => $this->password);
            $this->soapClient = new \SoapClient($this->wsdl);
            $this->connectionId = $this->soapClient->connect($credentials)->connectReturn;
            $this->status = self::CONNECTED;
            if ($this->logger) {
                $this->logger->debug(sprintf('Connected with connectionId %s', $this->connectionId));
            }

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
     */
    protected function loadXQueryResultFromCache($xquery, $typeOfQuery = ExistDB::FICHE)
    {
        if ($this->stopwatch) {
            $this->stopwatch->start('ExistDB::loadXQueryResultFromCache');
        }
        if ($this->logger) {
            $this->logger->debug('loadXQueryResultFromCache: ' . $xquery);
        }
        $cachedQuery = false;
        $fileName = md5($xquery);
        $fileName = sprintf('%s/%s/%s/%s', $this->getCacheDir(), $typeOfQuery, substr($fileName, 0, 1), substr($fileName, 1));
        if (is_file($fileName) && is_readable($fileName)) {
            if ((time() - filemtime($fileName)) < $this->cacheMaxAge) {
                $cachedQuery = file_get_contents($fileName);
            }
        }
        if ($this->stopwatch) {
            $this->stopwatch->stop('ExistDB::loadXQueryResultFromCache');
        }

        return $cachedQuery;
    }

    /**
     * Enregistre le résultat de la requête en cache
     */
    protected function saveXQueryToCache($xquery, $queryResult, $typeOfQuery = ExistDB::FICHE)
    {
        if ($this->logger) {
            $this->logger->debug('saveXQueryToCache: ' . $xquery);
        }
        $fileName = md5($xquery);
        $fileName = sprintf('%s/%s/%s/%s', $this->getCacheDir(), $typeOfQuery, substr($fileName, 0, 1), substr($fileName, 1));
        $dirName = dirname($fileName);
        if (!is_dir($dirName)) {
            mkdir($dirName, 0777, true);
        }
        file_put_contents($fileName, $queryResult);
    }
}
