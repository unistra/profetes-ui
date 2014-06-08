declare namespace cdm="http://cdm-fr.fr/2006/CDM-frSchema";

let $unistra := 'FR_RNE_0673021V_OR'
let $secteur-activite := "{{{secteur-activite}}}"

return <secteur-activite nom="{$secteur-activite}">{
for $f in collection('%collection%')/cdm:CDM[cdm:orgUnit/@id = $unistra]/cdm:program[cdm:qualification/cdm:qualificationDescription/cdm:subBlock[@userDefined = 'secteursActiviteUNERA'] = $secteur-activite]
order by number($f/cdm:level/cdm:subBlock/cdm:extension/order/@n), $f/cdm:programName/cdm:text[@language = 'fr-FR-TRL']/text()

return <formation id="{replace(lower-case($f/@id), '_', '-')}">{$f/cdm:programName/cdm:text[@language = 'fr-FR']/text()}</formation>
}</secteur-activite>
