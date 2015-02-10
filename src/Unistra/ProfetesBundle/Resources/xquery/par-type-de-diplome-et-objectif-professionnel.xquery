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
let $objectif-professionnel := "{{{objectif-professionnel}}}"

return <formations>{

for $formation in collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[(cdm:level/cdm:subBlock/cdm:extension/order/@n = $type-de-diplome) and (cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $objectif-professionnel)]
order by $formation/cdm:programName/cdm:text[@language = 'fr-FR-TRL']
return <formation id="{replace(lower-case($formation/@id), '_', '-')}">{$formation/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>

}</formations>
