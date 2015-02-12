(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $codeRne := '%code_rne%'
let $type-de-diplome := "{{{type-de-diplome}}}"

for $n in distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[cdm:level/cdm:subBlock/cdm:extension/order/@n = $type-de-diplome]/cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'champsDisciplinairesUNERA']/text())
order by $n
return <option value="{$n}">{$n}</option>
