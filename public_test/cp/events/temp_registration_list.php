<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
?>
<html>
<head>

	<style>
	
		body {
			font-family: Arial;
			font-size: 12px;
			margin: 0;
			padding: 0;
		}
		
		h1 {
			font-size: 2.5em;
			margin: 0;
			margin-bottom: 0.25em;
		}
		
		h2 {
			font-size: 1.75em;
			font-weight: normal;
			margin: 0;
			margin-bottom: 1em;
		}
		
		h3 {
			font-size: 1.5em;
			font-weight: bold;
			margin: 0;
			margin-bottom: 0.25em;
		}
		
		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
		}
		
		li {
			margin-bottom: 2em;
			page-break-inside: avoid;
		}
		
		div.third {
			clear: none;
			float: left;
			margin-right: 1%;
			width: 31.5%;
		}
		
		div.clear {
			clear: both;
		}
		
		div#header {
			background-color: #f2f2f2;
			border-bottom: 3px #ccc solid;
			padding: 15px 30px;
		}
		
		div#content {
			padding: 30px;
		}
		
		table {
			border: 3px #666 solid;
			border-collapse: collapse;
			width: 100%;
		}
		
		td, th {
			font-size: 14px;
			padding: 3px;
		}
		
		th {
			border-bottom: 2px #666 solid;
			text-align: left;
		}
		
		td {
			border-bottom: 1px #ccc solid;
		}
		
		a#print-labels {
			background: url(/cp/_assets/_images/icons/printer.png) left center no-repeat;
			color: #666;
			float: right;
			height: 16px;
			padding-left: 21px;
			text-decoration: none;
		}
		
		a#print-labels:hover {
			color: #333;
			text-decoration: underline;
		}
		
		@media print {
		
			body, td {
				font-size: 8pt;
			}
		
			div#header {
				display: none;
			}
			
		}
	
	</style>

</head>
<body>

	<div id="header">
		
		Select an event:
		
		<select name="eventId" onchange="location.href = 'temp_registration_list.php?event=' + this.options[this.selectedIndex].value">
<?
	if ($_GET['event'])
		$intEventId = $_GET['event'];

	$objEvent = new Event();
	$objEvent->loadAdminEvents();
	
	while ($objEvent->loadNext()) {
	
		if (!$intEventId)
			$intEventId = $objEvent->getId();
	
	?>
			<option value="<?= $objEvent->getId() ?>"<?= $intEventId == $objEvent->getId() ? ' selected="selected"' : '' ?>><?= $objEvent->getName() ?></option>
	<?
	}
?>
		</select>
<?
	
	$objEvent = new Event($intEventId);
	
	$objRegistration = new RegistrationX();
	$objRegistration->loadOldCompleteByEventId($objEvent->getId());
?>	
		<a href="print_labels.php?event=<?= $objEvent->getId() ?>" id="print-labels">Print Labels</a>
		
	</div>
<?

?>

	<div id="content">

		<h1>Event Registration List</h1>
		<h2><?= $objEvent->getName() ?></h2>

		<ul>
<?
	$arrFields = array(
		'ticketNumber'		=> 'Order #',
		'registrant'		=> 'Registrant',
		'package'			=> 'Package',
		'numberOfTickets'	=> '# of Tickets'
	);
	$arrData = array();
	
	$intTicketCount = 0;

	while ($objRegistration->loadNext()) {
	
		$intTicketCount += $objRegistration->getNumberOfTickets();
	
		$arrData[] = array(
			'ticketNumber'		=> $objRegistration->getRegistrationCode(),
			'registrant'		=> ucfirst($objRegistration->getLastName()) . ', ' . ucfirst($objRegistration->getFirstName()),
			'package'			=> $objRegistration->getPackageName(),
			'numberOfTickets'	=> $objRegistration->getNumberOfTickets() . ' ticket' . ($objRegistration->getNumberOfTickets() == 1 ? '' : 's')
		);
		
	}
	
	$objGrid = new DataGrid('orders', $arrFields, $arrData);

?>
	<strong><?= count($arrData) ?></strong> Total Registrations, <strong><?= $intTicketCount ?></strong> Total Tickets<br /><br />
<?
	
	echo $objGrid->draw();

	if (0) {
	?>
			<li>
				<div class="number-of-tickets"><?= $objRegistration->getNumberOfTickets() ?></div>
				<div style="margin-left: 65px;">
					<h3><?= ucfirst($objRegistration->getLastName()) ?>, <?= ucfirst($objRegistration->getFirstName()) ?> &mdash; <?= $objRegistration->getPackageName() ?></h3>
					Ticket #: <?= $objRegistration->getRegistrationCode() ?>
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<h3><?= ucfirst($objRegistration->getLastName()) ?>, <?= ucfirst($objRegistration->getFirstName()) ?> - <?= $objRegistration->getRegistrationCode() ?></h3>
				<div class="third">
					<?= $objRegistration->getAddress1() ?><br />
					<?= $objRegistration->getAddress2() ? $objRegistration->getAddress2() . '<br />': '' ?>
					<?= $objRegistration->getCity() ?>, <?= $objRegistration->getState() ?> <?= $objRegistration->getZipCode() ?><br /><br />
					<?= $objRegistration->getPhoneNumber() ? $objRegistration->getPhoneNumberDisplay() . '<br />' : ''?>
					<?= $objRegistration->getEmailAddress() ?><br />
				</div>
				<div class="third">
					<strong>Package:</strong> <?= $objRegistration->getPackageName() ?>	<br />
					<strong>Number of Tickets:</strong> <?= $objRegistration->getNumberOfTickets() ?><br />
					<strong>Amount Paid:</strong> <?= number_format($objRegistration->getAmountPaid(), 2) ?>
				</div>
				<div class="third">
					<strong>Registration Date:</strong> <?= date('n/j/Y', $objRegistration->getDateCreated()) ?>
				</div>
				<div class="clear"></div>
				
			</li>
	<?
	}
?>
		</ul>
		
	</div>

</body>
</html>

	