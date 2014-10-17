<?php

namespace Unistra\Profetes\Repository;

use Doctrine\Common\Cache\CacheProvider;
use Unistra\Profetes\eXist\eXistDB;
use Unistra\Profetes\ProgramId;
use Unistra\Profetes\XQuery;
use Unistra\Profetes\Program;

class ProfeteseXistRepository implements ProfetesRepository
{
    protected $existDb;
    protected $cache;
    protected $ttlProgram;
    protected $ttlQuery;

    public function __construct(eXistDB $existDb, CacheProvider $cache, $ttlProgram, $ttlQuery)
    {
        $this->existDb = $existDb;
        $this->cache = $cache;
        $this->ttlProgram = $ttlProgram;
        $this->ttlQuery = $ttlQuery;
    }

    /**
     * @param  ProgramId $programId
     * @return Program
     */
    public function getProgram(ProgramId $programId)
    {
        $this->cache->setNamespace('profetes.program');
        $resourcePath = $programId->getResourcePath();

        $xml = $this->cache->fetch($resourcePath);

        if (!$xml) {
            $xml = $this->existDb->getResource($resourcePath);
            $this->cache->save($resourcePath, $xml, $this->ttlProgram);
        }

        $program = new Program($xml);

        return $program;
    }

    /**
     * @param  XQuery  $query
     * @param  boolean $addXmlProlog
     * @return string
     */
    public function query(XQuery $query, $addXmlProlog = true)
    {
        $this->cache->setNamespace('profetes.xquery');
        $xquery = $query->getXQuery();
        $cacheId = md5($xquery);

        if ($result = $this->cache->fetch($cacheId)) {
            return $result;
        }

        $result = $this->existDb->xquery($xquery, $addXmlProlog);
        $this->cache->save($cacheId, $result, $this->ttlQuery);

        return $result;
    }
}
