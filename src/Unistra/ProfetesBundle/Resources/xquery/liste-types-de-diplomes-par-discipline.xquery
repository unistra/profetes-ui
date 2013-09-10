declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $discipline := 'Histoire'

for $n in distinct-values(collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'champsDisciplinairesUNERA']/text() = $discipline]/cdm:level/cdm:subBlock/cdm:extension/order/@n)
order by number($n)
return <option value="{$n}">{distinct-values(collection('/db/CDM-2009')/cdm:CDM/cdm:program/cdm:level/cdm:subBlock[cdm:extension/order/@n = $n]/cdm:subBlock/text())}</option>