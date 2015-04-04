<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
		
		$objBlog = new Blog($_GET['blog']);
		
		if (!$objBlog->delete())
			throw new RecordDeletionException($objBlog);
			
		$_SESSION['forms']['blog']['success'] = '<strong>' . $objBlog->getTitle() . '</strong> was deleted successfully!';
			
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		$_SESSION['forms']['blog']['error'] = $objException->getMessage() ? $objException->getMessage() : 'There was an error deleting the blog. Please try again';
		
		header('Location: index.php');
		exit;
		
	}