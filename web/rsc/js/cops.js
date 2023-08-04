$(document).ready(function() {
  $('html').height('100%');
  $('body').height('100%');

  if($('.login-panel').length!=0) {
    $(document).bind('mousemove', function(e) {
      $('.login-panel').addClass('active');
    });
    $(window).bind('keydown', function(e){
      $('.login-panel').addClass('active');
    });
  }

  $('.ajaxAction[data-trigger="click"]').on('click', function(){
    ajaxActionClick($(this));
  });

  $('.ajaxAction[data-trigger="change"]').on('change', function(){
    ajaxActionChange($(this));
  });

  // Interface Inbox
  // On s'appuie sur la présence du block "mailbox-controls"
  if ($('.mailbox-controls').length!=0) {
    enableMailboxControls();

    // Action sur la checkbox globale
    $('.checkbox-toggle').click(function () {
      let clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .fa-solid.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .fa-solid.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      enableMailboxControls();
      $(this).data('clicks', !clicks)
    })

    // Action sur les checkboxes individuelles
    $('.mailbox-messages input[type=\'checkbox\']').click(function() {
      enableMailboxControls();
    });

    // Pas d'actions individuelles pour le moment

    // Action sur le trash global
    $('.mailbox-controls button[data-action=\'trash\']').click(function() {
      if (!$(this).hasClass('disabled')) {
        let title = "Confirmation de la suppression";
        let get = [];
        location.search.replace('?', '').split('&').forEach(function(val) { let split = val.split("=", 2); get[split[0]] = split[1]; });
        let folder = get['subOnglet'];
        let message = "Les messages sélectionnés seront"+(folder=="trash" ? " définitivement" : "")+" supprimés.";
        let ids = $(".mailbox-messages input:checkbox:checked").map(function(){ return $(this).val(); }).get().join();
        let hrefConfirm = "/admin?onglet=inbox"+(folder!=undefined ? "&subOnglet="+get['subOnglet'] : "")+"&trash=1&ids="+ids;
        openConfirmModal(title, message, hrefConfirm);
      }
    });

    // Action sur le reply global
    // Action sur le transfert global
    // Action sur le refresh global
    // Action sur le bouton Précédent
    // Action sur le bouton Suivant
  }

  // Interface Compose
  if ($('.mailbox-read-info').length!=0) {
    $('.card-footer button').on('click', function() {
      $('#mailFrom').attr('disabled', false);
      $('#mailContent').html($('#noteEditable').html());
      $('#writeAction').val($(this).data('action'));
      $('form#writeForm').submit();
    });
    // Saisie dans le destinataire vérifie l'existence et transforme en joli badge en cas de saisie de ; ?
    // Sur un clic Brouillon, le message est enregistré dans les Brouillons. Note : comment gérer l'enregistrement des destinataires lors du Brouillon ?
    // Sur un clic Envoyer, le message est enregistré et envoyé
    // Sur un clic Annuler, on retourne au dossier Réception
    // La saisie du message doit pouvoir être mise en style.
    // Envisager à terme l'ajout de boutons pour styler
  }

  // Interface Compose
  if ($('.enquete-main-info').length!=0) {
	  $('.enquete-main-info a.nav-link').unbind().on('click', function() {
		  let tab = $(this).data('tab');
		  $('.enquete-main-info + div .note-frame').hide();
		  $(tab).show();
		  $('.enquete-main-info a.nav-link').removeClass('bg-primary');
		  $(this).addClass('bg-primary');
	  });
	  $('#writeForm input').on('blur', function() {
		  $(this).removeClass('border-danger');
	  });
	  
	  $('button[type="submit"]').on('click', function() {
		  $('#writeForm input[required]').each(function() {
			  if ($(this).val()=='') {
				  $(this).addClass('border-danger').focus();
				  return false;
			  }
		  }); 
	  });
	  $('.note-editable').on('blur', function(){
        $($(this).data('input')).html($(this).html());
      });
      $('.note-editable').each(function(){
        $($(this).data('input')).html($(this).html());
      });
  }
  
  if ($('textarea[data-resize="auto"]').length!=0) {
    $('textarea[data-resize="auto"]').on('keyup', function(){
      if ($(this).scrollTop()>0) {
        $(this).height($(this).height()+$(this).scrollTop()+10);
      }
    });
    $('textarea[data-resize="auto"]').trigger('keyup');
  }

  if ($('#calendar').length!=0) {
    stretchColspanEvents();
  }
  $('*[data-trigger="click"]').on('click', function() {
    switch ($(this).data('action')) {
      case 'display' :
        $($(this).data('target')).show();
      break;
      case 'toggle' :
        $($(this).data('target')).toggle();
      break;
      case 'submit' :
        if (controlerFormulaire($(this).data('target'))) {
          $($(this).data('target')).submit();
        } else {
          return false;
        }
      break;
    }
  });

  $('fieldset.collapsible i.feather').on('click', function() {
    $(this).parent().parent().toggleClass('collapsed');
    $(this).toggleClass('icon-chevron-right').toggleClass('icon-chevron-down');
  });
  $('fieldset button[data-bs-toggle="dropdown"]').on('click', function() {
    if ($(this).hasClass('show')) {
      $(this).removeClass('show');
      $(this).next().removeClass('show');
    } else {
      $(this).addClass('show');
      $(this).next().addClass('show');
    }
  });
  $('a.dropdown-item').on('click', function() {
    if ($('#reference').val()=='') {
      $('#reference').val($(this).data('abr'));
    } else {
      $('#reference').val($('#reference').val()+', '+$(this).data('abr'));
    }
    $('fieldset button[data-bs-toggle="dropdown"]').removeClass('show').next().removeClass('show');
    $('#reference').focus();
  });

  $('.accordion-button').on('click', function(){
    $(this).toggleClass('collapsed');
  });
	$('.enquete-main-info .nav a.nav-link').on('click', function(){
		$('.enquete-main-info + div .note-editor').hide();
		$('.enquete-main-info .nav a.nav-link').removeClass('bg-primary');
		$(this).addClass('bg-primary');
		$($(this).data('tab')).show();
		$($(this).data('tab')+' .note-editable').focus();
	});

    $('textarea').each(function(textarea) {
        $(this).height($(this)[0].scrollHeight);
    })

    ///////////////////////////////////////////////////
    // Start Tchat refresh
    // On cherche le bouton de refresh éventuel dans l'écran
    // Les boutons de refresh de tchat ont un data-ajax="refresh"
    /*
    if ($('button[data-ajax="refresh"').length!=0) {
        $('button[data-ajax="refresh"').each(function(){
            let obj = $(this);
            let target = obj.data('target');
            $(target).scrollTop($(target).scrollTop()+$(target+' > div:last').position().top)

            timer = setInterval(function() { refreshTchat(obj, true); }, 15000);
        });
    }
    ///////////////////////////////////////////////////

    ///////////////////////////////////////////////////
    // Start Navigation refresh
    // Les nouveaux messages dans le Tchat général
    timerNavigation = setInterval(function() { checkNotifications(); }, 15000);
    */
    ///////////////////////////////////////////////////
    
});
let timer = null;
let timerNavigation = null;

function stretchColspanEvents() {
	$('.fc-daygrid-event-harness[data-colspan!="0"]').each(function(){
		let tdWidth = $(this).width();
		let nbDays = $(this).data('colspan');
		$(this).css('right', -1*nbDays*(tdWidth+1));
    });
}

function openConfirmModal(title, message, hrefConfirm) {
  $('#modal-confirm').addClass('show').show().unbind().click(function() {
    closeModal('#modal-confirm');
  });
  $('#modal-confirm .modal-title').html(title);
  $('#modal-confirm .modal-body p').html(message);
  $('#modal-confirm .modal-footer a').attr('href', hrefConfirm);
  $('#modal-confirm button[data-dismiss="modal"]').unbind().click(function() {
    closeModal('#modal-confirm');
  });

}

function closeModal(id) {
  $(id).removeClass('show').hide();
}

function enableMailboxControls() {
  let checkeds = $('.mailbox-messages input[type=\'checkbox\']:checked').length;
  if (checkeds>1) {
    $('.mailbox-controls .fa-trash-alt').parent().removeClass('disabled');
    $('.mailbox-controls .fa-reply').parent().addClass('disabled');
    $('.mailbox-controls .fa-share').parent().addClass('disabled');
  } else if (checkeds==1) {
    $('.mailbox-controls .fa-trash-alt').parent().removeClass('disabled');
    $('.mailbox-controls .fa-reply').parent().removeClass('disabled');
    $('.mailbox-controls .fa-share').parent().removeClass('disabled');
  } else {
    $('.mailbox-controls .fa-trash-alt').parent().addClass('disabled');
    $('.mailbox-controls .fa-reply').parent().addClass('disabled');
    $('.mailbox-controls .fa-share').parent().addClass('disabled');
  }
}

function ajaxActionClick(obj) {
	let actions = obj.data('ajax').split(',');
	for (let oneAction of actions) {
	    switch (oneAction) {
            // Poster un message dans le tchat
            case 'tchat' :
                if (!obj.hasClass('disabled')) {
                    obj.addClass('disabled').addClass('fa-spin');
                    sendTchat(obj);
                }
                break;
            // Rafraichir le tchat
            case 'refresh':
                if (!obj.hasClass('disabled')) {
                    obj.addClass('disabled').addClass('fa-spin');
                    refreshTchat(obj, false);
                }
                break;
			case 'csvExport' :
				csvExport(obj);
				break;
		}
	}
}

function ajaxActionChange(obj) {
    let id = obj.attr('id');
    let actions = obj.data('ajax').split(',');
    for (let oneAction of actions) {
    switch (oneAction) {
      case 'saveData' :
        saveData(obj);
      break;
      case 'checkLangue' :
        checkLangues();
        checkCaracFormulaire();
      break;
      case 'checkCarac' :
        if (checkCaracteristique(id)) {
          checkCaracteristiques();
          if (id=='carac-carrure') {
            $('#carac-health-points').val(20+3*obj.val());
          } else if (id=='carac-charme' || id=='carac-education') {
            let maxValue = ($('#carac-charme').val()>$('#carac-education').val() ? $('#carac-charme').val() : $('#carac-education').val());
            $('#card-langues select').each(function(idx) {
              if (idx<maxValue) {
                $(this).show();
              } else {
                $(this).hide();
              }
            });
            checkLangues();
          }
          checkCaracFormulaire();
        }
      break;
      default :
        console.log(oneAction+" n'est pas prévu comme valeur d'action Ajax.");
      break;
    }
  }
}

function saveData(obj) {
  let data = {'action': 'dealWithAjax', 'ajaxAction': 'saveData', 'field': obj.attr('id'), 'value': obj.val(), 'id': obj.data('objid')};

  // On a un appel ajax pour rechercher les équivalences au numéro
  $.post(
  	ajaxurl,
    data,
    function(response) {
      try {
        obj = JSON.parse(response);
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
    }
  ).done(function(response) {
    obj = JSON.parse(response);
    displayToast(obj.toastContent);
  });

}

function checkLangues() {
  let bln_cardLangue_OK = true;
  $('#card-langues select').each(function(){
    if ($(this).is(':visible') && $(this).val()=='') {
      bln_cardLangue_OK = false;
    }
  });
  if (bln_cardLangue_OK) {
    $('#card-langues').addClass('card-success').removeClass('card-warning');
  } else {
    $('#card-langues').addClass('card-warning').removeClass('card-success');
  }
}

function checkCaracFormulaire() {
  if ($('#card-caracs').hasClass('card-success') && $('#card-langues').hasClass('card-success')) {
    $('#card-submit').addClass('card-success').removeClass('card-danger');
    $('#card-submit p').hide();
    $('button[type="submit"]').removeClass('disabled');
  } else {
    $('#card-submit').addClass('card-danger').removeClass('card-success');
    $('#card-submit p').show();
    $('button[type="submit"]').addClass('disabled');
  }
}

function checkCaracteristiques() {
  let sumCaracPoints = 0;
  $('#card-caracs input').each(function(){
    sumCaracPoints += $(this).val()*1;
  });
  if (sumCaracPoints==21) {
    $('#card-caracs').addClass('card-success').removeClass('card-danger card-warning');
  } else if (sumCaracPoints>21) {
    $('#card-caracs').addClass('card-danger').removeClass('card-success card-warning');
    displayToast('<div class="toast show bg-danger"><div class="toast-header"><i class="fas fa-exclamation-circle ms-2"></i><strong class="me-auto">OOps</strong></div><div class="toast-body">Vous ne disposez que de 21 points à répartir entre vos caractéristiques.</div></div>');
  } else {
    $('#card-caracs').addClass('card-warning').removeClass('card-danger card-success');
  }
    // On doit vérifier que le nombre de points disponibles n'a pas été complétement dépensé.
    // Sinon, ça a l'air d'être bon.
}

function checkCaracteristique(id) {
  let bln_OK = true;
  let value = $('#'+id).val();
  // On va vérifier que la caractéristique est supérieure ou égale à 2 et inférieure ou égale à 5
  if (value<2) {
    displayToast('<div class="toast show bg-warning"><div class="toast-header"><i class="fas fa-exclamation-circle ms-2"></i><strong class="me-auto">OOps</strong></div><div class="toast-body">Une caractéristique ne peut pas être plus basse que 2.</div></div>');
    bln_OK = false;
    $('#card-caracs').addClass('card-danger').removeClass('card-success card-warning');
  } else if (value>5) {
    displayToast('<div class="toast show bg-warning"><div class="toast-header"><i class="fas fa-exclamation-circle ms-2"></i><strong class="me-auto">OOps</strong></div><div class="toast-body">Une caractéristique ne peut pas être plus élevée que 5.</div></div>');
    bln_OK = false;
    $('#card-caracs').addClass('card-danger').removeClass('card-success card-warning');
  } else if (value==5) {
    // On doit vérifier si elle vaut 5 que c'est la seule
    $('#'+id).addClass('maxCarac');
    if ($('.maxCarac').length>1) {
      displayToast('<div class="toast show bg-warning"><div class="toast-header"><i class="fas fa-exclamation-circle ms-2"></i><strong class="me-auto">OOps</strong></div><div class="toast-body">Une seule caractéristique peut être initialisée à 5.</div></div>');
      bln_OK = false;
      $('#card-caracs').addClass('card-danger').removeClass('card-success card-warning');
    }
  }
  return bln_OK;
}

function displayToast(value) {
  $('#toastPlacement').append(value);
  $('#toastPlacement .toast:last-child').delay(5000).hide(0);
}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
// Gestion des contrôles des formulaires
//////////////////////////////////////////////////////////////////////////////
// Fonction d'entrée pour rediriger vers le bon contrôle
function controlerFormulaire(formId) {
  let blnOk = false;
  switch (formId) {
    case '#creerNewEvent' :
      blnOk = controlerFormulaireCreerNewEvent();
    break;
  }
  return blnOk;
}
// Fonction de contrôler pour création d'un nouvel événement
function controlerFormulaireCreerNewEvent() {
  $('#creerNewEvent .border-danger').removeClass('border-danger');
  let blnOk = true;
  if (estVide('#eventLibelle')) {
    $('#eventLibelle').addClass('border-danger');
    blnOk = false;
    console.log('Le libellé doit être saisi.');
  }
  /*
  if (!estDateValide('#dateDebut')) {
    $('#dateDebut').addClass('border-danger');
    blnOk = false;
    console.log('La date de début doit être saisie.');
  }
  if (!estDateValide('#dateFin')) {
    $('#dateFin').addClass('border-danger');
    blnOk = false;
    console.log('La date de fin doit être saisie.');
  }
  */
  if (blnOk && estDateSuperieure('#dateDebut', '#dateFin')) {
    $('#dateDebut').addClass('border-danger');
    $('#dateFin').addClass('border-danger');
    blnOk = false;
    console.log('La date de début doit être inférieure à la date de fin.');
  } else if (blnOk && estDateEgale('#dateDebut', '#dateFin') && $('#event_allday:checked')==undefined) { // Seulement égale ici
    if ($('#event_start_hour').val()>$('#event_end_hour').val()) {
      $('#event_start_hour').addClass('border-danger');
      $('#event_end_hour').addClass('border-danger');
      blnOk = false;
    }
    if ($('#event_start_hour').val()==$('#event_end_hour').val() && $('#event_start_minutes').val()>$('#event_end_minutes').val()) {
      $('#event_start_minutes').addClass('border-danger');
      $('#event_end_minutes').addClass('border-danger');
      blnOk = false;
    }
  }
  return blnOk;
}

//////////////////////////////////////////////////////////////////////////////
// Fonctions utilitaires
// L'objet passé en paramètre est-il vide ?
function estVide(target) {
  return ($(target).val().trim()=='');
}
// La valeur de l'objet passé en paramètre est-il une date
function estDateValide(target) {
  let blnOk = true;
  if (estVide(target)) {
    blnOk = false;
  } else {
    let datas = $(target).val().trim().split('/');
    if (datas.length!=3) {
      blnOk = false;
    } else {
//      let d = new Date(datas[2], datas[1], datas[0]);
//      blnOk = (d.getDate()==datas[0]*1 && d.getMonth()==datas[1]*1);
    }
  }
  return blnOk;
}
// La première date est-elle supérieure à la deuxième
function estDateSuperieure(dStart, dEnd) {
  let dataStart = $(dStart).val().trim().split('/');
  let dateStart = new Date(dataStart[2], dataStart[1], dataStart[0]);
  let dataEnd   = $(dEnd).val().trim().split('/');
  let dateEnd   = new Date(dataEnd[2], dataEnd[1], dataEnd[0]);
  return (dateStart>dateEnd);
}
// La première date est-elle égale à la deuxième
function estDateEgale(dStart, dEnd) {
  let dataStart = $(dStart).val().trim().split('/');
  let dataEnd   = $(dEnd).val().trim().split('/');
  console.log(dataStart);
  console.log(dataEnd);
  return (dataStart[2]==dataEnd[2] && dataStart[1]==dataEnd[1] && dataStart[0]==dataEnd[0]);
}
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function csvExport(obj) {
  let data = {'action': 'dealWithAjax', 'ajaxAction': 'csvExport', 'natureId': obj.data('natureid')};

  // On a un appel ajax pour rechercher les équivalences au numéro
  $.post(
    ajaxurl,
    data,
    function(response) {
      try {
        obj = JSON.parse(response);
      } catch (e) {
        console.log("error: "+e);
        console.log(response);
      }
  }).done(function(response) {
    obj = JSON.parse(response);
    displayToast(obj.toastContent);
    /*
  }).done(function(response) {
	let a = $("<a />", {
               href: "data:text/csv," 
                     + URL.createObjectURL(new Blob([response], {
                         type:"text/csv"
                       })),
               "download":"filename.csv"
            });	
            $("body").append(a);
            a[0].click();
    */
  });
  
}


$('input[name="repeatEnd"]').on('click', function(){
    if ($('#repeatEndDate').is(':checked')) {
        $('#endDateValue').prop('readonly', false);
        $('#endRepetitionValue').prop('readonly', true);
    } else if ($('#repeatEndValue').is(':checked')) {
        $('#endDateValue').prop('readonly', true);
        $('#endRepetitionValue').prop('readonly', false);
    } else {
        $('#endDateValue').prop('readonly', true);
        $('#endRepetitionValue').prop('readonly', true);
    }
});

$('#repeatStatus').on('click', function(){
    if ($(this).is(':checked')) {
        $('#mec_meta_box_period_form').show();
        $('#mec_meta_box_recursive_form').show();
        $('#mec_meta_box_custom_form').show();
    } else {
        $('#mec_meta_box_period_form').hide();
        $('#mec_meta_box_recursive_form').hide();
        $('#mec_meta_box_custom_form').hide();
    }
});

$('#allDayEvent').on('click', function(){
    if ($(this).is(':checked')) {
        $('#heureDebut').prop('readonly', true);
        $('#heureFin').prop('readonly', true);
    } else {
        $('#heureDebut').prop('readonly', false);
        $('#heureFin').prop('readonly', false);
    }
});

$('#customEvent').on('click', function(){
    if ($(this).is(':checked')) {
        $('#customDay').removeClass('disabled');
        $('#customDayWeek').removeClass('disabled');
        $('#customMonth').removeClass('disabled');
        $('#repeatInterval').prop('readonly', true);
    } else {
        $('#customDay').addClass('disabled');
        $('#customDayWeek').addClass('disabled');
        $('#customMonth').addClass('disabled');
        $('#repeatInterval').prop('readonly', false);
    }
});


///////////////////////////////////////////////////
// FONCTIONS RELATIVES A LA BARRE DE NAVIGATION
///////////////////////////////////////////////////
/**
 * Permet de vérifier la présence de nouvelles notifications
 * @since v1.23.08.05 
 */
function checkNotifications() {
    let data = {'action': 'dealWithAjax', 'ajaxAction': 'checkNotif'};
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch(e) {
                
            }
        }
    ).done(function(response) {
        try {
            obj = JSON.parse(response);
            if (obj.comment && obj.comment!='') {
                if ($('.fa-comment').siblings().length!=0) {
                    $('.fa-comment').siblings().remove();
                }
                $('.fa-comment').parent().append(obj.comment);
            }
        } catch(e) {
            
        }
    });
}
///////////////////////////////////////////////////

///////////////////////////////////////////////////
// FONCTIONS RELATIVES AU TCHAT
///////////////////////////////////////////////////

/**
 * Permet d'envoyer une instruction via l'inerface de Tchat
 * @since v1.23.08.05 
 */
function sendTchat(obj) {
    let id = obj.attr('id');
    let ajaxAction = obj.data('ajax');
    let target = obj.data('target');
    let value = $(target).val();
    let data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'value': value};
    // On envoie par ajax les informations saisies
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch(e) {
                
            }
        }
    ).done(function(response) {
        $(target).val('');
        $('#'+id).removeClass('disabled').removeClass('fa-spin');
        try {
            obj = JSON.parse(response);
            if (obj.toastContent) {
                displayToast(obj.toastContent);
            }
            $('button[data-ajax="refresh"').each(function(){
                let obj = $(this);
                refreshTchat(obj, true);
            });
        } catch(e) {
            
        }
    });
}

/**
 * Permet de rafraichir le dialog du Tchat
 * @since v1.23.08.05 
 */
function refreshTchat(obj, blnAutoRefresh) {
    let id = obj.attr('id');
    let ajaxAction = obj.data('ajax');
    let target = obj.data('target');
    let refreshed = $(target+' > div:last').data('refreshed');
    let data = {'action': 'dealWithAjax', 'ajaxAction': ajaxAction, 'refreshed': refreshed};
    // On envoie par ajax les informations saisies
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch(e) {
                
            }
        }
    ).done(function(response) {
        $('#'+id).removeClass('disabled').removeClass('fa-spin');
        try {
            obj = JSON.parse(response);
            if (obj.tchatContent) {
                $(target).append(obj.tchatContent);
            }
            if (obj.toastContent && !blnAutoRefresh) {
                displayToast(obj.toastContent);
            }
        } catch(e) {
        }
    });
}
///////////////////////////////////////////////////
// FIN FONCTIONS RELATIVES AU TCHAT
///////////////////////////////////////////////////
