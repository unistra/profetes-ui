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

return <type-diplome nom="{$type-de-diplome}">{
for $f in collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[cdm:level/cdm:subBlock/cdm:subBlock/text() = $type-de-diplome]
order by $f/cdm:programName/cdm:text[@language = 'fr-FR-TRL']

return <formation id="{replace(lower-case($f/@id), '_', '-')}">{$f/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>

}</type-diplome>
