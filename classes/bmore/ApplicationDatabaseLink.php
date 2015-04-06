<?
class ApplicationDatabaseLink extends DatabaseLink {

	public function __construct() {

    // LOCAL
    // parent::__construct('127.0.0.1', 'root', '015100', 'bmorearoundtown');

    // PROD
    // parent::__construct('bmorearoundtown.ipowermysql.com', 'bmorearoundtown', '0ri0135&r4v3n5', 'bmorearoundtown');

    // TEST
    parent::__construct('bmorearoundtown.ipowermysql.com', 'bmorearoundtown', '0ri0135&r4v3n5', 'bmorearoundtown_test');
	}

}
