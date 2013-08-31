declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $composante_id := '{{{composante}}}'

let $composante := distinct-values(collection('/db/CDM-2009')/cdm:CDM/cdm:orgUnit/cdm:orgUnit[@id = $composante_id]/cdm:orgUnitName/cdm:text)

let $types := distinct-values(collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/cdm:orgUnit/@id = $composante_id]/cdm:program/cdm:level/cdm:subBlock/cdm:subBlock)


return <types-diplome><composante>{$composante}</composante>{

for $type in $types
  let $formations := collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/cdm:orgUnit/@id = $composante_id]/cdm:program[cdm:level/cdm:subBlock/cdm:subBlock = $type]
  order by $type
  return <type-diplome name="{$type}">{for $formation in $formations order by $formation/cdm:programName/cdm:text[@language='fr-FR']/text() return <formation><id>{replace(lower-case($formation/@id), '_', '-')}</id><nom>{$formation/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</nom></formation>}</type-diplome>
}</types-diplome>
