declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $type-de-diplome := '{{{type-de-diplome}}}'

return <type-diplome nom="{$type-de-diplome}">{
for $f in collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[cdm:level/cdm:subBlock/cdm:subBlock/text() = $type-de-diplome]
order by $f/cdm:programName/cdm:text[@language = 'fr-FR']

return <formation id="{replace(lower-case($f/@id), '_', '-')}">{$f/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>

}</type-diplome>
