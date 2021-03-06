<?php

include_once __DIR__.'/../../core.php';

// Impostazione filtri di default a tutte le selezioni la prima volta
if (!isset($_SESSION['dashboard']['idtecnici'])) {
    $rs = $dbo->fetchArray("SELECT an_anagrafiche.idanagrafica AS id FROM an_anagrafiche INNER JOIN (an_tipianagrafiche_anagrafiche INNER JOIN an_tipianagrafiche ON an_tipianagrafiche_anagrafiche.idtipoanagrafica=an_tipianagrafiche.idtipoanagrafica) ON an_anagrafiche.idanagrafica=an_tipianagrafiche_anagrafiche.idanagrafica WHERE deleted=0 AND descrizione='Tecnico'");

    $_SESSION['dashboard']['idtecnici'] = ["'-1'"];

    for ($i = 0; $i < count($rs); ++$i) {
        $_SESSION['dashboard']['idtecnici'][] = "'".$rs[$i]['id']."'";
    }
}

if (!isset($_SESSION['dashboard']['idstatiintervento'])) {
    $rs = $dbo->fetchArray('SELECT idstatointervento AS id, descrizione FROM in_statiintervento');

    $_SESSION['dashboard']['idstatiintervento'] = ["'-1'"];

    for ($i = 0; $i < count($rs); ++$i) {
        $_SESSION['dashboard']['idstatiintervento'][] = "'".$rs[$i]['id']."'";
    }
}

if (!isset($_SESSION['dashboard']['idtipiintervento'])) {
    $rs = $dbo->fetchArray('SELECT idtipointervento AS id, descrizione FROM in_tipiintervento');

    $_SESSION['dashboard']['idtipiintervento'] = ["'-1'"];

    for ($i = 0; $i < count($rs); ++$i) {
        $_SESSION['dashboard']['idtipiintervento'][] = "'".$rs[$i]['id']."'";
    }
}

if (!isset($_SESSION['dashboard']['idzone'])) {
    $rs = $dbo->fetchArray('SELECT id, descrizione FROM an_zone');

    $_SESSION['dashboard']['idzone'] = ["'-1'"];

    for ($i = 0; $i < count($rs); ++$i) {
        $_SESSION['dashboard']['idzone'][] = "'".$rs[$i]['id']."'";
    }
}

// Stati intervento
$checks = '';
$count = 0;
$total = 0;

$rs = $dbo->fetchArray('SELECT idstatointervento AS id, descrizione, colore FROM in_statiintervento ORDER BY descrizione ASC');
$total = count($rs);

$allchecksstati = '';
for ($i = 0; $i < count($rs); ++$i) {
    $attr = '';

    foreach ($_SESSION['dashboard']['idstatiintervento'] as $idx => $val) {
        if ($val == "'".$rs[$i]['id']."'") {
            $attr = 'checked="checked"';
            ++$count;
        }
    }

    $checks .= "<li><input type='checkbox' id='idstato_".$rs[$i]['id']."' value=\"".$rs[$i]['id'].'" '.$attr." onclick=\"session_set_array( 'dashboard,idstatiintervento', '".$rs[$i]['id']."' ); $('#calendar').fullCalendar('refetchEvents'); update_counter( 'idstati_count', $('#idstati_ul').find('input:checked').length );\"> <label for='idstato_".$rs[$i]['id']."'> <span class='badge' style=\"color:#333; background:".$rs[$i]['colore'].';">'.$rs[$i]['descrizione']."</span></label></li>\n";

    $allchecksstati .= "session_set_array( 'dashboard,idstatiintervento', '".$rs[$i]['id']."', 0 ); ";
}

if ($count == $total) {
    $class = 'btn-success';
} elseif ($count == 0) {
    $class = 'btn-danger';
} else {
    $class = 'btn-warning';
}

if ($total == 0) {
    $class = 'btn-primary disabled';
}
?>

<!-- Filtri -->
<div class="row">
	<!-- STATI INTERVENTO -->
	<div class="dropdown col-md-3">
		<a class="btn <?php echo $class ?> btn-block" data-toggle="dropdown" href="javascript:;" id="idstati_count"><i class="fa fa-filter"></i> <?php echo tr('Stati intervento'); ?> (<?php echo $count.'/'.$total ?>) <i class="caret"></i></a>

		<ul class="dropdown-menu" role="menu" id="idstati_ul">
			<?php echo $checks ?>
			<div class="btn-group pull-right">
				<button  id="selectallstati" onclick="<?php echo $allchecksstati; ?>" class="btn btn-primary btn-xs" type="button"><?php echo tr('Tutti'); ?></button>
				<button id="deselectallstati" class="btn btn-danger btn-xs" type="button"><i class="fa fa-times"></i></button>
			</div>

		</ul>
	</div>

<?php
// Tipi intervento
$checks = '';
$count = 0;
$total = 0;

$rs = $dbo->fetchArray('SELECT idtipointervento AS id, descrizione FROM in_tipiintervento ORDER BY descrizione ASC');
$total = count($rs);

$allcheckstipi = '';
for ($i = 0; $i < count($rs); ++$i) {
    $attr = '';

    foreach ($_SESSION['dashboard']['idtipiintervento'] as $idx => $val) {
        if ($val == "'".$rs[$i]['id']."'") {
            $attr = 'checked="checked"';
            ++$count;
        }
    }

    $checks .= "<li><input type='checkbox' id='idtipo_".$rs[$i]['id']."' value=\"".$rs[$i]['id'].'" '.$attr." onclick=\"session_set_array( 'dashboard,idtipiintervento', '".$rs[$i]['id']."' ); $('#calendar').fullCalendar('refetchEvents'); update_counter( 'idtipi_count', $('#idtipi_ul').find('input:checked').length );\"> <label for='idtipo_".$rs[$i]['id']."'> ".$rs[$i]['descrizione']."</label></li>\n";

    $allcheckstipi .= "session_set_array( 'dashboard,idtipiintervento', '".$rs[$i]['id']."', 0 ); ";
}

if ($count == $total) {
    $class = 'btn-success';
} elseif ($count == 0) {
    $class = 'btn-danger';
} else {
    $class = 'btn-warning';
}

if ($total == 0) {
    $class = 'btn-primary disabled';
}
?>
	<!-- TIPI DI INTERVENTO -->
	<div class="dropdown col-md-3">
		<a class="btn <?php echo $class ?> btn-block" data-toggle="dropdown" href="javascript:;" id="idtipi_count"><i class="fa fa-filter"></i> <?php echo tr('Tipi intervento'); ?> (<?php echo $count.'/'.$total ?>) <i class="caret"></i></a>

		<ul class="dropdown-menu" role="menu" id="idtipi_ul">
			<?php echo $checks ?>
			<div class="btn-group pull-right">
				<button  id="selectalltipi" onclick="<?php echo $allcheckstipi; ?>" class="btn btn-primary btn-xs" type="button"><?php echo tr('Tutti'); ?></button>
				<button id="deselectalltipi" class="btn btn-danger btn-xs" type="button"><i class="fa fa-times"></i></button>
			</div>

		</ul>

	</div>

<?php
// Tecnici
$checks = '';
$count = 0;
$total = 0;
$totale_tecnici = 0; // conteggia tecnici eliminati e non

$rs = $dbo->fetchArray("SELECT an_anagrafiche.idanagrafica AS id, ragione_sociale FROM an_anagrafiche INNER JOIN (an_tipianagrafiche_anagrafiche INNER JOIN an_tipianagrafiche ON an_tipianagrafiche_anagrafiche.idtipoanagrafica=an_tipianagrafiche.idtipoanagrafica) ON an_anagrafiche.idanagrafica=an_tipianagrafiche_anagrafiche.idanagrafica WHERE deleted=0 AND descrizione='Tecnico' ORDER BY ragione_sociale ASC");
$total = count($rs);

$totale_tecnici += $total;

$allchecktecnici = '';
for ($i = 0; $i < count($rs); ++$i) {
    $attr = '';

    foreach ($_SESSION['dashboard']['idtecnici'] as $idx => $val) {
        if ($val == "'".$rs[$i]['id']."'") {
            $attr = 'checked="checked"';
            ++$count;
        }
    }

    $checks .= "<li><input type='checkbox' id='tech_".$rs[$i]['id']."' value=\"".$rs[$i]['id'].'" '.$attr." onclick=\"session_set_array( 'dashboard,idtecnici', '".$rs[$i]['id']."' ); $('#calendar').fullCalendar('refetchEvents'); update_counter( 'idtecnici_count', $('#idtecnici_ul').find('input:checked').length );\"> <label for='tech_".$rs[$i]['id']."'> ".$rs[$i]['ragione_sociale']."</label></li>\n";

    $allchecktecnici .= "session_set_array( 'dashboard,idtecnici', '".$rs[$i]['id']."', 0 ); ";
}

// TECNICI ELIMINATI
$rs = $dbo->fetchArray("SELECT an_anagrafiche.idanagrafica AS id, ragione_sociale FROM an_anagrafiche INNER JOIN (an_tipianagrafiche_anagrafiche INNER JOIN an_tipianagrafiche ON an_tipianagrafiche_anagrafiche.idtipoanagrafica=an_tipianagrafiche.idtipoanagrafica) ON an_anagrafiche.idanagrafica=an_tipianagrafiche_anagrafiche.idanagrafica WHERE deleted=1 AND descrizione='Tecnico' ORDER BY ragione_sociale ASC");
$total = count($rs);

$totale_tecnici += $total;

if ($total > 0) {
    $checks .= "<li><hr>Tecnici eliminati:</li>\n";
    for ($i = 0; $i < count($rs); ++$i) {
        $attr = '';

        foreach ($_SESSION['dashboard']['idtecnici'] as $idx => $val) {
            if ($val == "'".$rs[$i]['id']."'") {
                $attr = 'checked="checked"';
                ++$count;
            }
        }

        $checks .= "<li><input type='checkbox' id='tech_".$rs[$i]['id']."' value=\"".$rs[$i]['id'].'" '.$attr." onclick=\"session_set_array( 'dashboard,idtecnici', '".$rs[$i]['id']."' ); $('#calendar').fullCalendar('refetchEvents'); update_counter( 'idtecnici_count', $('#idtecnici_ul').find('input:checked').length );\"> <label for='tech_".$rs[$i]['id']."'> ".$rs[$i]['ragione_sociale']."</label></li>\n";

        $allchecktecnici .= "session_set_array( 'dashboard,idtecnici', '".$rs[$i]['id']."', 0 ); ";
    } // end for
} // end if

if ($count == $totale_tecnici) {
    $class = 'btn-success';
} elseif ($count == 0) {
    $class = 'btn-danger';
} else {
    $class = 'btn-warning';
}

if ($totale_tecnici == 0) {
    $class = 'btn-primary disabled';
}

?>
	<!-- TECNICI -->
	<div class="dropdown col-md-3">
		<a class="btn <?php echo $class ?> btn-block" data-toggle="dropdown" href="javascript:;" id="idtecnici_count"><i class="fa fa-filter"></i> <?php echo tr('Tecnici'); ?> (<?php echo $count.'/'.$totale_tecnici ?>) <i class="caret"></i></a>

		<ul class="dropdown-menu" role="menu" id="idtecnici_ul">
			<?php echo $checks ?>
			<div class="btn-group pull-right">
				<button id="selectalltecnici" onclick="<?php echo $allchecktecnici; ?>" class="btn btn-primary btn-xs" type="button"><?php echo tr('Tutti'); ?></button>
				<button id="deselectalltecnici" class="btn btn-danger btn-xs" type="button"><i class="fa fa-times"></i></button>
			</div>
		</ul>
	</div>


<?php
// Zone
$checks = '';
$count = 0;
$total = 0;

$rs = $dbo->fetchArray('SELECT id, descrizione FROM an_zone ORDER BY descrizione ASC');
$total = count($rs);

for ($i = 0; $i < count($rs); ++$i) {
    $attr = '';

    foreach ($_SESSION['dashboard']['idzone'] as $idx => $val) {
        if ($val == "'".$rs[$i]['id']."'") {
            $attr = 'checked="checked"';
            ++$count;
        }
    }

    $checks .= "<li><input type='checkbox' id='idzone_".$rs[$i]['id']."' value=\"".$rs[$i]['id'].'" '.$attr." onclick=\"session_set_array( 'dashboard,idzone', '".$rs[$i]['id']."' ); $('#calendar').fullCalendar('refetchEvents'); update_counter( 'idzone_count', $('#idzone_ul').find('input:checked').length );\"> <label for='idzone_".$rs[$i]['id']."'> ".$rs[$i]['descrizione']."</label></li>\n";

    $allcheckzone .= "session_set_array( 'dashboard,idzone', '".$rs[$i]['id']."', 0 ); ";
}

if ($count == $total) {
    $class = 'btn-success';
} elseif ($count == 0) {
    $class = 'btn-danger';
} else {
    $class = 'btn-warning';
}

if ($total == 0) {
    $class = 'btn-primary disabled';
}
?>
	<!-- ZONE -->
	<div class="dropdown col-md-3">
		<a class="btn <?php echo $class ?> btn-block" data-toggle="dropdown" href="javascript:;" id="idzone_count"><i class="fa fa-filter"></i> <?php echo tr('Zone'); ?> (<?php echo $count.'/'.$total ?>) <i class="caret"></i></a>

		<ul class="dropdown-menu" role="menu" id="idzone_ul">
			<?php echo $checks ?>
			<div class="btn-group pull-right">
				<button id="selectallzone" onclick="<?php echo $allcheckzone; ?>" class="btn btn-primary btn-xs" type="button"><?php echo tr('Tutti'); ?></button>
				<button id="deselectallzone" class="btn btn-danger btn-xs" type="button"><i class="fa fa-times"></i></button>
			</div>
		</ul>
	</div>
</div>
<br>
<?php
$qp = "SELECT id, idcontratto, richiesta, data_richiesta, 'intervento' AS ref, (SELECT descrizione FROM in_tipiintervento WHERE idtipointervento=co_righe_contratti.idtipointervento) AS tipointervento FROM co_righe_contratti WHERE idcontratto IN( SELECT id FROM co_contratti WHERE idstato IN(SELECT id FROM co_staticontratti WHERE pianificabile = 1) ) AND idintervento IS NULL
UNION SELECT id, idcontratto, '', data_scadenza, 'ordine', (SELECT descrizione FROM in_tipiintervento WHERE idtipointervento='ODS') AS tipointervento FROM co_ordiniservizio WHERE idcontratto IN( SELECT id FROM co_contratti WHERE idstato IN(SELECT id FROM co_staticontratti WHERE pianificabile = 1) ) AND idintervento IS NULL ORDER BY data_richiesta ASC";
$rsp = $dbo->fetchArray($qp);

if (!empty($rsp)) {
    echo '
<div class="row">
    <div class="col-xs-12 col-md-10">';
}

echo '
<div id="calendar"></div>';

if (!empty($rsp)) {
    echo '
    </div>

    <div id="external-events" class="hidden-xs hidden-sm col-md-2">
        <h4>'.tr('Interventi da pianificare').'</h4>';

    foreach ($rsp as $r) {
        echo '
        <div class="fc-event " data-id="'.$r['id'].'" data-idcontratto="'.$r['idcontratto'].'">'.Translator::dateToLocale($r['data_richiesta']).' ('.$r['tipointervento'].')'.(!empty($r['richiesta']) ? ' - '.$r['richiesta'] : '').'</div>';
    }

    echo '
    </div>
</div>';
}

$vista = get_var('Vista dashboard');
if ($vista == 'mese') {
    $def = 'month';
} elseif ($vista == 'giorno') {
    $def = 'agendaDay';
} else {
    $def = 'agendaWeek';
}
?>

<script type="text/javascript">
	$(document).ready(function() {
        // Comandi seleziona tutti
        $('#selectallstati').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = true;
            });

            update_counter( 'idstati_count', $('#idstati_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#selectalltipi').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = true;
            });

            update_counter( 'idtipi_count', $('#idtipi_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#selectalltecnici').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = true;
            });

            update_counter( 'idtecnici_count', $('#idtecnici_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#selectallzone').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                this.checked = true;
            });

            update_counter( 'idzone_count', $('#idzone_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        // Comandi deseleziona tutti
        $('#deselectallstati').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                if( this.checked == true ) session_set_array( 'dashboard,idstatiintervento', this.value, 1 );
                this.checked = false;
            });

            update_counter( 'idstati_count', $('#idstati_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#deselectalltipi').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                if( this.checked == true ) session_set_array( 'dashboard,idtipiintervento', this.value, 1 );
                this.checked = false;
            });

            update_counter( 'idtipi_count', $('#idtipi_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#deselectalltecnici').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                if( this.checked == true ) session_set_array( 'dashboard,idtecnici', this.value, 1 );
                this.checked = false;
            });

            update_counter( 'idtecnici_count', $('#idtecnici_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        $('#deselectallzone').click(function(event) {
            $(this).parent().parent().find('li input[type=checkbox]').each(function() { //loop through each checkbox
                if( this.checked == true ) session_set_array( 'dashboard,idzone', this.value, 1 );
                this.checked = false;
            });

            update_counter( 'idzone_count', $('#idzone_ul').find('input:checked').length );
            $('#calendar').fullCalendar('refetchEvents');
        });

        // Creazione del calendario
		create_calendar();
	});

	function create_calendar(){
        $('#external-events .fc-event').each(function() {

			// store data so the calendar knows to render an event upon drop
			$(this).data('event', {
				title: $.trim($(this).text()), // use the element's text as the event title
				stick: false // maintain when user navigates (see docs on the renderEvent method)
			});

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});

		var calendar = $('#calendar').fullCalendar({
            locale: globals.locale,
<?php
if (!empty(get_var('Visualizzare la domenica sul calendario'))) {
    echo '
            hiddenDays: [ 0 ],';
}
?>
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			timeFormat: 'H:mm',
            slotLabelFormat: "H:mm",
			slotDuration: '00:15:00',
            defaultView: '<?php echo $def; ?>',
<?php
if (!empty(get_var('Abilitare orario lavorativo'))) {
    echo "
            minTime: '08:00:00',
            maxTime: '20:00:00',";
}
?>
            lazyFetching: true,
			selectHelper: true,
			eventLimit: false, // allow "more" link when too many events
			allDaySlot: false,
            loading: function(isLoading, view) {
                if(isLoading) {
 					$('#tiny-loader').fadeIn();
                } else {
                    $('#tiny-loader').hide();
                }
            },
<?php
if (Modules::getPermission('Interventi') == 'rw') {
    ?>
            droppable: true,
            drop: function(date, jsEvent, ui, resourceId) {
                data = moment(date).format("YYYY-MM-DD");
				ora_dal = moment(date).format("HH:mm");
                ora_al = moment(date).add(1, 'hours').format("HH:mm");

                var name = ($(this).data('ref') == 'ordine') ? 'idordineservizio' : 'idcontratto_riga';

                launch_modal('<?php echo tr('Pianifica intervento'); ?>', globals.rootdir + '/add.php?id_module=<?php echo Modules::getModule('Interventi')['id'] ?>&data='+data+'&orario_inizio='+ora_dal+'&orario_fine='+ora_al+'&ref=dashboard&idcontratto=' + $(this).data('idcontratto') + '&' + name + '=' + $(this).data('id'), 1);

                $(this).remove();

                $('#bs-popup').on('hidden.bs.modal', function () {
                    $('#calendar').fullCalendar('refetchEvents');
                });
            },

            selectable: true,
			select: function(start, end, allDay) {
				data = moment(start).format("YYYY-MM-DD");
				ora_dal = moment(start).format("HH:mm");
				ora_al = moment(end).format("HH:mm");

                launch_modal('<?php echo tr('Aggiungi intervento'); ?>', globals.rootdir + '/add.php?id_module=<?php echo Modules::getModule('Interventi')['id'] ?>&ref=dashboard&data='+data+'&orario_inizio='+ora_dal+'&orario_fine='+ora_al, 1 );

				$('#calendar').fullCalendar('unselect');
			},

            editable: true,
            eventDrop: function(event,dayDelta,minuteDelta,revertFunc) {
				$.get(globals.rootdir + "/modules/dashboard/ajaxreq.php?op=update_intervento&id="+event.id+"&idintervento="+event.idintervento+"&timeStart="+moment(event.start).format("YYYY-MM-DD HH:mm")+"&timeEnd="+moment(event.end).format("YYYY-MM-DD HH:mm"), function(data,response){
					if( response=="success" ){
						data = $.trim(data);
						if( data!="ok" ){
							alert(data);
							$('#calendar').fullCalendar('refetchEvents');
							revertFunc();
						}
						else{
							return false;
						}
					}
				});
			},
            eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
				$.get(globals.rootdir + "/modules/dashboard/ajaxreq.php?op=update_intervento&id="+event.id+"&idintervento="+event.idintervento+"&timeStart="+moment(event.start).format("YYYY-MM-DD HH:mm")+"&timeEnd="+moment(event.end).format("YYYY-MM-DD HH:mm"), function(data,response){
					if( response=="success" ){
						data = $.trim(data);
						if(data != "ok"){
							alert(data);
							$('#calendar').fullCalendar('refetchEvents');
							revertFunc();
						}
						else{
							return false;
						}
					}
				});
			},
<?php

}
?>
			eventAfterRender: function(event, element) {
				element.find('.fc-title').html(event.title);
<?php

if (get_var('Utilizzare i tooltip sul calendario') == '1') {
    ?>
				$.get(globals.rootdir + "/modules/dashboard/ajaxreq.php?op=get_more_info&id="+event.idintervento+"&timeStart="+moment(event.start).format("YYYY-MM-DD HH:mm")+"&timeEnd="+moment(event.end).format("YYYY-MM-DD HH:mm"), function(data,response){
					if( response=="success" ){
						data = $.trim(data);
						if( data!="ok" ){
							element.tooltipster({
								content: data,
								animation: 'grow',
								contentAsHTML: true,
								hideOnClick: true,
								onlyOne: true,
								speed: 200,
								delay: 100,
								maxWidth: 400,
								theme: 'tooltipster-shadow',
								touchDevices: true,
								trigger: 'hover',
								position: 'left'
							});

						}
						else{
							return false;
						}
					}
                });
<?php

}
?>
			},
            events: {
				url: globals.rootdir + "/modules/dashboard/ajaxreq.php?op=get_current_month",
                type: 'GET',
				error: function() {
					alert('<?php echo tr('Errore durante la creazione degli eventi'); ?>');
				}
			}
		});
	}
</script>
