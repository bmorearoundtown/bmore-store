<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	$GLOBALS['config']->addPageCSSManual( 'blogs/blogs.css' );
	
	$objBlog = new Blog($_GET['blog']);
	
	require_once( 'header.php' );
?>

	<ul id="quick-links" class="quick-links hidden-xs hidden-sm list-unstyled">
		<li><a href="/cp/blogs/previewWebVersion.php?blog=<?= $objBlog->getId() ?>" class="muted"><i class="fa fa-eye"></i>Preview</a></li>
		<li><a href="/cp/blogs/edit.php?blog=<?= $objBlog->getId() ?>" class="text-warning"><i class="fa fa-pencil"></i>Edit Blog</a></li>
		<li><a href="#" class="text-danger"><i class="fa fa-times"></i> Delete Blog</a></li>
		<li><a href="/cp/blogs/index.php"><i class="fa fa-arrow-left"></i> Back to blogs</a></li>
	</ul>
	
	<div class="menu visible-xs visible-sm" style="margin-top: 20px;">
		
		<form name="menuNav"> 
			<select name="navMenu" class="form-control" onChange="window.location=document.menuNav.navMenu.options[document.menuNav.navMenu.selectedIndex].value">
				<option value="#"> - Select an action -</option>
				<option value="/cp/blogs/edit.php?blog=<?= $objBlog->getId() ?>">Edit Blog</option>
				<option value="#">Delete blog</option>
				<option value="/cp/events/index.php">Back to all blogs</option>
			</select>
		</form>
		
	</div>
	
	<div class="page-header">
		<h1 style="font-size: 2.25em; color: #222;"><?= $objBlog->getTitle() ?></h1>
	</div>
	
	<div class="row">
	
		<div class="col-sm-12 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">
					<h3 class="panel-title">Blog Details</h3>
				</div>
				
				<div class="panel-body">
					
					<dl class="event-details-list">
				
						<div class="row">
							<dt class="col-xs-2">Title:</dt>
							<dd class="col-xs-10"><?= $objBlog->getTitle() ?></dd>				
						</div>

						<div class="row">
							<dt class="col-xs-2">Author:</dt>				
							<dd class="col-xs-10"><?= $objBlog->getAuthor() ?></dd>
						</div>
						
						<div class="row">
							<dt class="col-xs-2">Image:</dt>
							<dd class="col-xs-10">
								
								<? 
									
									$sourceImage = $_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/blogs/' . $objBlog->getBlogCode() . ".png";
									
									if( file_exists( $sourceImage ) ) { 
			
								?>
									
									<img class="img-thumbnail" src="<?= $objBlog->getLogoImageUrl() ?>"/>
								
								<? } else { ?>
									
									N/A
									
								<? } ?>
								
							</dd>				
						</div>

						<div class="row">
							
							<label class="col-xs-2">Introduction:</label>
							
							<div class="col-xs-10">
							
								<div class="well well-sm description"> 
									<p><?= nl2br($objBlog->getIntroduction()) ?></p>
								</div>
								
							</div>
							
						</div>

						<div class="row">
							
							<label class="col-xs-2">Content:</label>
							
							<div class="col-xs-10">
							
								<div class="well well-sm description"> 
									<p><?= nl2br($objBlog->getContent()) ?></p>
								</div>
								
							</div>
							
						</div>

						<div class="row">
							
							<label class="col-xs-2">Signature:</label>
							
							<div class="col-xs-10">
							
								<div class="well well-sm description"> 
									<p><?= nl2br($objBlog->getSignature()) ?></p>
								</div>
								
							</div>
							
						</div>
						
					</dl>
			
				</div>
				
			</div>
						
		</div>
			
		<div class="col-sm-12 col-md-6">

			<div class="row">
				
				<div class="col-xs-12">

					<div class="panel panel-info">

						<div class="panel-heading">
							<h3 class="panel-title">Blog Entry Information</h3>
						</div>
						
						<div class="panel-body">
				
							<dl class="blog-list">

								<div class="row">
									<dt class="col-xs-4 col-md-2">Code:</dt>
									<dd class="col-xs-8 col-md-10"><?=  $objBlog->getBlogCode() ?></dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Date Created:</dt>
									<dd class="col-xs-8 col-md-10"><?= date('M j, Y g:i A', $objBlog->getDateCreated()) ?> (ET)</dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Is Active:</dt>
									<dd class="col-xs-8 col-md-10"><?= (  $objBlog->getIsActive() ? 'yes' : 'no')  ?></dd>
								</div>
								
							</dl>
					
						</div>
						
					</div>
				
				</div>
				
			</div>
			
		</div>
	
	</div>
		
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');
?>