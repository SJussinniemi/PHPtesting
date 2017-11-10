<?php


class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
	public function testPageTitle(AcceptanceTester $I)
	{
		$I->amOnPage('/');
		$I->seeInTitle('PHPTest');	
	}
	
	public function iSeeFrontpageTexts(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Testing');
		$I->see('Hello world');		
    }
	
	public function fillingInputsWorks(AcceptanceTester $I)
	{
		$I->amOnPage('/');
		$I->fillField('user[name]','TestingMan');
		$I->fillField('user[email]','TestMan@gmail.com');
		$I->selectOption('user[gender]','Male');
		
		codecept_debug($I->grabTextFrom('#user_name'));
		codecept_debug($I->grabTextFrom('#user_email'));
		
		$I->cantSeeCheckboxIsChecked('Animal');
		$I->click('Update');
		$I->amOnPage('/results');
		
	}
	
	public function testSeeAllDBItemsOnPage(AcceptanceTester $I)
	{
		include 'db.php';
		$mysqli = new mysqli($server, $username, $password, $database);
		$res = $mysqli->query("SELECT Job,Firstname,Lastname FROM contact ORDER BY id DESC");
		
		for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) 
		{
			$res->data_seek($row_no);
			$row = $res->fetch_assoc();
			
			$I->amOnPage('/');
			$I->see($row['Job']);
			$I->see($row['Firstname']);
			$I->see($row['Lastname']);
		}		
	}

}
