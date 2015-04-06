<?php
	
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );
	
	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'blogs' );

	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
?>

<div class="container">
	
	<div class="row">
		
 		<div id="content" class="blog">
                
            <h1>Blog / Articles </h1>

			<hr>
					
			<?php include("blog6.php"); ?>
			
			<hr>
			
			<?php include("blog5.php"); ?>
			
			<hr>
			
			<?php include("blog4.php"); ?> 
			
			<hr>
			
			<?php include("blog3.php"); ?> 
			
			<hr>
			
			<?php include("blog2.php"); ?> 
			
			<hr>
			
			<?php include("blog1.php"); ?>
					
		</div>		
	
	</div>

</div>

<div id="newsletterWrapper" class="wrapper">
	
	<div class="container-fluid">
		
		<?php include("newsletter.php"); ?>
		
	</div>
	
</div>
	
<?php require_once("footer.php"); ?>

</body>

</html>
