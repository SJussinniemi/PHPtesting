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
	

}
