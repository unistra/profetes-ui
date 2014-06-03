<?php

namespace Unistra\Profetes\Repository;

use Unistra\Profetes\ProgramId;
use Unistra\Profetes\XQuery;

interface ProfetesRepository
{
    public function getProgram(ProgramId $id);

    public function query(XQuery $query);
}
