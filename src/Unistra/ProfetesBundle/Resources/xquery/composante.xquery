declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $composante_id := '{{{composante}}}'

let $composante := distinct-values(collection('%collection%')/cdm:CDM/cdm:orgUnit/cdm:orgUnit[@id = $composante_id]/cdm:orgUnitName/cdm:text)

let $types := distinct-values(collection('%collection%')/cdm:CDM[cdm:orgUnit/cdm:orgUnit/@id = $composante_id]/cdm:program/cdm:level/cdm:subBlock/cdm:extension/order/@n)


return <types-diplome><composante>{$composante}</composante>{

for $type in $types
  let $formations := collection('%collection%')/cdm:CDM[cdm:orgUnit/cdm:orgUnit/@id = $composante_id]/cdm:program[cdm:level/cdm:subBlock/cdm:extension/order/@n = $type]
  let $nomDuType := distinct-values(collection('%collection%')/cdm:CDM/cdm:program/cdm:level[cdm:subBlock/cdm:extension/order/@n = $type]/cdm:subBlock/cdm:subBlock)
  order by number($type)
  return <type-diplome name="{$nomDuType}">{for $formation in $formations order by $formation/cdm:programName/cdm:text[@language='fr-FR-TRL']/text() return <formation><id>{replace(lower-case($formation/@id), '_', '-')}</id><nom>{$formation/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</nom></formation>}</type-diplome>
}</types-diplome>
