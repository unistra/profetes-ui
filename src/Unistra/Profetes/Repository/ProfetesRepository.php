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

namespace Unistra\Profetes\Repository;

use Unistra\Profetes\ProgramId;
use Unistra\Profetes\XQuery;

interface ProfetesRepository
{
    public function getProgram(ProgramId $id);

    public function query(XQuery $query);
}
