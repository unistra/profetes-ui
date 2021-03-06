declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'

return <types-diplomes>{

for $n in distinct-values(collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program/cdm:level/cdm:subBlock/cdm:extension/order/@n)
order by number($n)

return <type-diplome order="{$n}">{distinct-values(collection('/db/CDM-2009')/cdm:CDM/cdm:program/cdm:level/cdm:subBlock[cdm:extension/order/@n = $n]/cdm:subBlock/text())}</type-diplome>

}</types-diplomes>