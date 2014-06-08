declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $type-de-diplome := "{{{type-de-diplome}}}"
let $objectif-professionnel := "{{{objectif-professionnel}}}"

return <formations>{

for $formation in collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[(cdm:level/cdm:subBlock/cdm:extension/order/@n = $type-de-diplome) and (cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $objectif-professionnel)]
order by $formation/cdm:programName/cdm:text[@language = 'fr-FR-TRL']
return <formation id="{replace(lower-case($formation/@id), '_', '-')}">{$formation/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>

}</formations>
