(:
    Copyright Université de Strasbourg (2015)

    Daniel Bessey <daniel.bessey@unistra.fr>

    This software is a computer program whose purpose is to display course information
    extracted from a Profetes database on a website.

    See LICENSE for more details.
:)
declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $codeRne := '%code_rne%'
let $secteur-activite := 'Droit'

let $types := distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $secteur-activite]/cdm:level/cdm:subBlock/cdm:extension/order/@n)

return <formations><secteur-activite>{$secteur-activite}</secteur-activite>{
  for $type in $types
    let $formations := collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $codeRne]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $secteur-activite and cdm:level/cdm:subBlock/cdm:extension/order/@n = $type]
    let $nomDuType := distinct-values(collection('%collection%')/cdm:CDM/cdm:program/cdm:level[cdm:subBlock/cdm:extension/order/@n = $type]/cdm:subBlock/cdm:subBlock)
  order by number($type)
  return
    <type-diplome nom="{$nomDuType}">{
        for $formation in $formations order by $formation/cdm:programName/cdm:text[@language = 'fr-FR-TRL']/text()
        return <formation>
            <id>{replace(lower-case($formation/@id), '_', '-')}</id>
            <nom>{$formation/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</nom>
        </formation>}
    </type-diplome>
}</formations>