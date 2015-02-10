(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $codeRne := '%code_rne%'
let $objectif-professionnel := "{{{objectif-professionnel}}}"

for $n in distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA']/text() = $objectif-professionnel]/cdm:level/cdm:subBlock/cdm:extension/order/@n)
order by number($n)
return <option value="{$n}">{distinct-values(collection('%collection%')/cdm:CDM/cdm:program/cdm:level/cdm:subBlock[cdm:extension/order/@n = $n]/cdm:subBlock/text())}</option>
