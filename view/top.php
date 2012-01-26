<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="pl" />
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="stylesheet" href="view/index.css" type="text/css" />
        <title>szkolea</title>
        <script type="text/javascript" src="js/index.js"></script>
        <script type="text/javascript" src="js/onloader.js"></script>

<?php

if(basename($_SERVER['PHP_SELF'], '.php') == 'addcomm')
{
?>

	<link rel="stylesheet" type="text/css" href="css/calendar-eightysix-v1.1-default.css" media="screen" />

        <script type="text/javascript" src="js/dynamic_selects.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>
        
	<script type="text/javascript" src="js/mootools-1.2.4-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="js/calendar-eightysix-v1.1.js"></script>
        <script type="text/javascript" src="js/hints_addcomm.js"></script>

        <script type="text/javascript">
		window.addEvent('domready', function() {

			//Example XI
			var calendarXIa = new CalendarEightysix('exampleXIa', { 'disallowUserInput': true, 'minDate': 'today',  'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			var calendarXIb = new CalendarEightysix('exampleXIb', { 'disallowUserInput': true, 'minDate': 'tomorrow', 'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			calendarXIa.addEvent('change', function(date) {
				date = date.clone().increment(); //At least one day higher; so increment with one day
				calendarXIb.options.minDate = date; //Set the minimal date
				if(calendarXIb.getDate().diff(date) >= 1) calendarXIb.setDate(date); //If the current date is lower change it
				else calendarXIb.render(); //Always re-render
			});

                        //Example XI
			var calendarXIc = new CalendarEightysix('exampleXIc', { 'disallowUserInput': true, 'minDate': 'today',  'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			var calendarXId = new CalendarEightysix('exampleXId', { 'disallowUserInput': true, 'minDate': 'tomorrow', 'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			calendarXIc.addEvent('change', function(date) {
				date = date.clone().increment(); //At least one day higher; so increment with one day
				calendarXId.options.minDate = date; //Set the minimal date
				if(calendarXId.getDate().diff(date) >= 1) calendarXId.setDate(date); //If the current date is lower change it
				else calendarXId.render(); //Always re-render
			});
		});
        </script>

<?php
}
else if(basename($_SERVER['PHP_SELF'], '.php') == 'addserv')
{
?>

        <link rel="stylesheet" type="text/css" href="css/calendar-eightysix-v1.1-default.css" media="screen" />

        <script type="text/javascript" src="js/dynamic_selects.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>
        
	<script type="text/javascript" src="js/mootools-1.2.4-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="js/calendar-eightysix-v1.1.js"></script>
        <script type="text/javascript" src="js/hints_addserv.js"></script>

        <script type="text/javascript">
		window.addEvent('domready', function() {

			//Example XI
			var calendarXIa = new CalendarEightysix('exampleXIa', { 'disallowUserInput': true, 'minDate': 'today',  'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			var calendarXIb = new CalendarEightysix('exampleXIb', { 'disallowUserInput': true, 'minDate': 'tomorrow', 'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			calendarXIa.addEvent('change', function(date) {
				date = date.clone().increment(); //At least one day higher; so increment with one day
				calendarXIb.options.minDate = date; //Set the minimal date
				if(calendarXIb.getDate().diff(date) >= 1) calendarXIb.setDate(date); //If the current date is lower change it
				else calendarXIb.render(); //Always re-render
			});
		});
        </script>

<?php
}
else if(basename($_SERVER['PHP_SELF'], '.php') == 'comm' && isset($_POST['trener_1']) && $_POST['trener_1'] == 'oferta_form')
{
?>

        <link rel="stylesheet" type="text/css" href="css/calendar-eightysix-v1.1-default.css" media="screen" />

	<script type="text/javascript" src="js/mootools-1.2.4-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="js/calendar-eightysix-v1.1.js"></script>
        <script type="text/javascript" src="js/hints_offer.js"></script>

        <script type="text/javascript">
		window.addEvent('domready', function() {

			//Example XI
			var calendarXIa = new CalendarEightysix('exampleXIa', { 'disallowUserInput': true, 'minDate': 'today',  'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			var calendarXIb = new CalendarEightysix('exampleXIb', { 'disallowUserInput': true, 'minDate': 'tomorrow', 'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			calendarXIa.addEvent('change', function(date) {
				date = date.clone().increment(); //At least one day higher; so increment with one day
				calendarXIb.options.minDate = date; //Set the minimal date
				if(calendarXIb.getDate().diff(date) >= 1) calendarXIb.setDate(date); //If the current date is lower change it
				else calendarXIb.render(); //Always re-render
			});
		});
        </script>

<?php
}
else if(basename($_SERVER['PHP_SELF'], '.php') == 'index')
{
?>
        <script type="text/javascript" src="js/dynamic_selects.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>

        <link rel="stylesheet" type="text/css" href="css/calendar-eightysix-v1.1-default.css" media="screen" />

	<script type="text/javascript" src="js/mootools-1.2.4-core.js"></script>
	<script type="text/javascript" src="js/mootools-1.2.4.4-more.js"></script>
	<script type="text/javascript" src="js/calendar-eightysix-v1.1.js"></script>

        <script type="text/javascript">
		window.addEvent('domready', function() {

			//Example XI
			var calendarXIa = new CalendarEightysix('exampleXIa', { 'disallowUserInput': true, 'minDate': 'today',  'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
			var calendarXIb = new CalendarEightysix('exampleXIb', { 'disallowUserInput': true, 'minDate': 'tomorrow', 'alignX': 'left', 'alignY': 'bottom', 'offsetX': -4 });
                        document.getElementById('exampleXIa').value = '';
                        document.getElementById('exampleXIb').value = '';
			calendarXIa.addEvent('change', function(date) {
				//date = date.clone().increment(); //At least one day higher; so increment with one day
				//calendarXIb.options.minDate = date; //Set the minimal date
				//if(calendarXIb.getDate().diff(date) >= 1) calendarXIb.setDate(date); //If the current date is lower change it
				//else calendarXIb.render(); //Always re-render
			});
		});
        </script>
<?php
}
else if(basename($_SERVER['PHP_SELF'], '.php') == 'admin')
{
?>
        <script type="text/javascript" src="js/dynamic_selects.js"></script>
        <script type="text/javascript" src="js/admin.js"></script>
        <script type="text/javascript" src="js/ajax_admin.js"></script>
<?php
}
else if(basename($_SERVER['PHP_SELF'], '.php') == 'log')
{
?>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/easy.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/hints_reg.js"></script>
<?php
}
else
{
?>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/easy.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
<?php
}

 ?>

    </head>

<?php

    include('view/html/top.html');

?>