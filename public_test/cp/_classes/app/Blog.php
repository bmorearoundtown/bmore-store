<?
class Blog extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('title');
		$this->_('introduction');
		$this->_('content');
		$this->_('date', 0, 'datetime');
		$this->_('signature');
		$this->_('author');
		$this->_('imageUrl');
		$this->_('blogCode');
		$this->_('dateCreated', 0, 'datetime');
		$this->_('datePublished', 0, 'datetime');
		$this->_('dateModified', 0, 'datetime');
		$this->_('isActive', 0);
		
		parent::__construct( 'Blogs', $id );
	
	}
	
	public function loadForAdmin() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName} 
		");
	
	}
	
	public function loadForForm() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		isActive = 1
			ORDER BY	dateCreated ASC
		");
	
	}

	public function loadByBlogCode($strBlogCode) {
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		blogCode = '{$strBlogCode}'
		");
	}

	public function getLogoImageUrl() {
		return '/_assets/_images/logos/blogs/' . $this->getBlogCode() . '.png';
	}
	
	public function generateBlogCode() {
	
		$strLetters = 'BCDFGHJKLMNPQRSTVWXYZ';
		
		do {
		
			$strBlogCode = $strLetters[rand(0, strlen($strLetters) - 1)] . $strLetters[rand(0, strlen($strLetters) - 1)] . $strLetters[rand(0, strlen($strLetters) - 1)];
			
			$objBlog = new Blog();
			$objBlog->loadByBlogCode($strBlogCode);
		
		} while (count($objBlog));
		
		$this->blogCode = $strBlogCode;
	
	}
	
}