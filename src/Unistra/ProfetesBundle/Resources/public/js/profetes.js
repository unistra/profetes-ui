var listes = ['Lev', 'Disc', 'Prof'];
var listesApi1 = ['level', 'discipline', 'profession'];
var listesApi2 = ['discipline', 'level', 'level'];
var selections = [['Lev', 'Disc'], ['Disc', 'Lev'], ['Prof', 'Lev']];
var scenario = 1;
var url = '/formations/api/';
var msgAttente = '<p><img src="/fileadmin/templates/unistra/images/icones/focus/loading.gif" height="16" width="16" align="middle">Chargement en cours, merci de patienter</p>';

function updateContent(control, url) {
  $('div#res').empty().html(msgAttente).show();
  $('#' + control).empty().hide();
  $.get(url, function(data) {
    $('div#res').empty();
    $('#' + control).html(data).show();
    if (control == 'res') {
      $('p.legende').show();
    }
  });
}

function showScenario(scenario, combos) {
  $('#fScen' + scenario).show();
  $('#p' + combos[0] + scenario).show();
  $('#p' + combos[1] + scenario).hide();
}

function hideScenario(scenario, combos) {
  $('#fScen' + scenario).hide();
  $('#fScen' + scenario + ' p').hide();
}

function switchScenario(scenario, combos) {
  var scenari = [0, 0, 0];
  if (scenario) {
    scenari[scenario -1] = 1;
  }

  $('p.legende').hide();

  for(var i = 0; i < scenari.length; i++) {
    var selectedScenarioId = 'div#scenario li a#scen' + (i + 1);
    if (scenari[i]) {
      showScenario(i + 1, combos);
      $(selectedScenarioId).addClass('checked');
    } else {
      $(selectedScenarioId).removeClass('checked');
      hideScenario(i + 1, combos);
    }
  }

  //charger pour la première liste déroulante
  var apiUrl = url + scenario + '/' + listesApi1[scenario - 1] + '/all/';
  var control = 'cbo' + selections[scenario - 1][0] + scenario;
  updateContent(control, apiUrl);
}

$(document).ready(function() {

  $('ul#prof_liste').hide();
  $('div#scenario').show();

  $('div#titre_page h1').html('Rechercher un diplôme');
  document.title = 'Rechercher un diplôme';

  switchScenario(scenario, selections[0]);

  $('div#scenario ul a').click(function() {
    scenario = parseInt($(this).attr('id').substring(4));
    var choix = selections[scenario - 1][0];
    switchScenario(scenario, selections[scenario - 1]);
    return false;
  });

  $('select.cbo1').change(function() {
    var selected = $(this).val();
    var control = selections[scenario - 1][1] + scenario;
    $('p.legende').hide();
    if (selected && scenario) {
      var apiUrl = url + scenario + '/' + listesApi1[scenario - 1] + '/' + encodeURIComponent(selected) + '/' + listesApi2[scenario - 1] + '/all/';
      updateContent('cbo' + control, apiUrl);
      $('#p' + control).show();
    } else {
      $('div#res').empty().hide();
      $('#p' + control).hide()
    }
  });

  $('select.cbo2').change(function() {
    var parentSelected = $('#fScen' + scenario + ' select.cbo1').val();
    var selected = $(this).val();
    if (selected && scenario && parentSelected) {
      var apiUrl = url + scenario + '/' + listesApi1[scenario - 1] + '/' + encodeURIComponent(parentSelected) + '/' + listesApi2[scenario - 1] + '/' + encodeURIComponent(selected) + '/';
      var control = 'res';
      updateContent(control, apiUrl);
    } else {
      $('div#res').empty().hide();
      $('p.legende').hide();
    }
  });

});