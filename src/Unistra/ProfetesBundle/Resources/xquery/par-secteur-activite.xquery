declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $secteur-activite := '{{{secteur-activite}}}'

return <secteur-activite nom="{$secteur-activite}">{
for $f in collection('/db/CDM-2009')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $secteur-activite]
order by $f/cdm:programName/cdm:text[@language = 'fr-FR-TRL']

return <formation id="{replace(lower-case($f/@id), '_', '-')}">{$f/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>
}</secteur-activite>
