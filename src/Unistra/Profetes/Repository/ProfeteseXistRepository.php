<?php

namespace Unistra\Profetes\Repository;

use Unistra\Profetes\eXist\eXistDB;
use Unistra\Profetes\Cache\Cache;
use Unistra\Profetes\ProgramId;
use Unistra\Profetes\XQuery;
use Unistra\Profetes\Program;

class ProfeteseXistRepository implements ProfetesRepository
{
    protected $existDb;

    protected $resourceCache;

    protected $queryCache;

    public function __construct(eXistDB $existDb, Cache $resourceCache, Cache $queryCache)
    {
        $this->existDb = $existDb;
        $this->resourceCache = $resourceCache;
        $this->queryCache = $queryCache;
    }

    /**
     * @return Program
     */
    public function getProgram(ProgramId $programId)
    {
        $resourcePath = $programId->getResourcePath();

        $xml = $this->resourceCache->fetch($resourcePath);

        if (!$xml) {
            $xml = $this->existDb->getResource($resourcePath);
            $this->resourceCache->save($resourcePath, $xml);
        }

        $program = new Program($xml);

        return $program;
    }

    /**
     * @return string
     */
    public function query(XQuery $query, $addXmlProlog = true)
    {
        $xquery = $query->getXQuery();

        if ($result = $this->queryCache->fetch($xquery)) {
            return $result;
        }

        $result = $this->existDb->xquery($xquery, $addXmlProlog);
        $this->queryCache->save($xquery, $result);

        return $result;
    }
}
