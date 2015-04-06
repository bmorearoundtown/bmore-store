<?
class BlogX extends Blog {

	public function __construct( $intId = 0 ) {
	
		$this->_('blogCount', 0);
		
		parent::__construct( $intId );
	
	}

}