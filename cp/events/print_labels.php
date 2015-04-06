<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/config.php');
	
	$objEvent = new Event($_GET['event']);
	
	$objRegistration = new RegistrationX();
	$objRegistration->loadCompleteByEventId($objEvent->getId());
?>
<!doctype html>
<html>
<head>

	<title><?= $objEvent->getName() ?> &mdash; Registration Labels</title>
	
	<link rel="stylesheet" href="/cp/_assets/_css/labels.css" />

</head>
<body>

	<div class="sheet">
	

<?
	$intCounter = 0;

	while ($objRegistration->loadNext()) {
	?>
		<div class="label">
			<strong><?= ucfirst($objRegistration->getLastName()) ?>, <?= ucfirst($objRegistration->getFirstName()) ?></strong>
			<?= $objRegistration->getPackageName() ?><br />
			<em><?= $objRegistration->getNumberOfTickets() ?> Package<?= $objRegistration->getNumberOfTickets() == 1 ? '' : 's' ?></em>
		</div>
	<?
	
		if (++$intCounter == 30) {
		?>

			
	</div>
	
	<div class="sheet">
	

		<?
		
			$intCounter = 0;
		
		}
		
		if ($intCounter % 3 == 0) {
		?>
		<?
		}
	
	}
?>
		
	</div>

</body>
</html>
