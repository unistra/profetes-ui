declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'

for $n in distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program/cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'champsDisciplinairesUNERA']/text())
order by $n
return <option value="{$n}">{$n}</option>