declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $type-de-diplome := '{{{type-de-diplome}}}'

for $n in distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[cdm:level/cdm:subBlock/cdm:extension/order/@n = $type-de-diplome]/cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'champsDisciplinairesUNERA']/text())
order by $n
return <option value="{$n}">{$n}</option>