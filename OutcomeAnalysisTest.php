<?php

namespace Tests\Browser\Feature\Report;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\HomePage;


class OutcomeAnalysisTest extends DuskTestCase
{
    public function setUp():void
    {
        parent::setUp();

        $this->browse(
            function (Browser $browser) {
                if (env('APP_HOME') == 'localhost') {
                    $this->user = factory(User::class)->create();
                    $this->user->role = 'dashboard_admin';
                    $this->user->home_site = internalCode();
                    $this->user->save();

                    $browser->pause(5000);

                    $this->displayName = $this->user->display_name;
                    $this->threadId = null;

                    $browser->visit(new LoginPage)
                        ->loginFactoryUser($this->user)
                        ->on(new HomePage);
                } elseif (env('APP_HOME') == 'humbleyardff') {
                    $this->userId = 334;
                    $this->displayName = 'Automation Admin User';
                    $browser->visit(new LoginPage)
                        ->loginAdminUser();
                } else {
                    $this->userId = 2806;
                    $this->displayName = 'Automation AdminUser';
                    $browser->visit(new LoginPage)
                        ->loginAdminUser();
                }
                $browser->on(new HomePage)
                ->clickReports()
                ->clickLink('Outcome Analysis','a');
            }
        );
    }
/**
     * Test if the Outcome Analysis page loads and displays all the parameters in the page.
     *
     * @return void
     */
    public function test_if_outcome_analysis_page_loads_and_displays_all_the_parameters_in_page()
    {
        $this->browse(function (Browser $browser) {
    $browser->assertSee('Outcome Analysis')
            ->assertSee('Period')
            ->assertSee('Request Type')
            ->assertSee('Form')
           // ->assertSee('User Group')
            ->assertSee('Filter')
            ->assertSee('Export')
            ->assertSee('Export')
            ->assertSee('Last 12 months')
            ->pause(2000)
            ->assertSee('Outcome Analysis')
            ->assertSee('Outcome Analysis (%)')
            ->assertSee('User')
            ->assertSee('Outcome');
        });

    }
/**
     * Test if the user can filter the Outcome Analysis Report in reports page
     *
     * @return void
     */
    public function test_if_user_can_filter_the_outcome_analysis_report()
    {
        $this->browse(function (Browser $browser) {
    $browser->select('period', 'last-12-months')
            ->pause(2000);
    $browser->select('type', 'proxy');
    $browser->pause(2000)
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__select')
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__content-wrapper > ul > li:nth-child(4) > span > span')
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__content-wrapper > ul > li:nth-child(9) > span > span')
            ->pause(2000)
            ->press('Filter');
    $browser->pause(3000)
            ->clickAudit()                 
            ->pause(3000)
         // ->assertSee('user fliter the Request analysis report') 
            ->pause(2000);     

        });

    }

    /**
     * Test if the user can export the Request Analysis Report
     *
     * @return void
     */
    public function test_if_user_can_export_the_request_analysis_report()
    {
        $this->browse(function (Browser $browser) {
    $browser->select('period', 'last-12-months')
            ->pause(2000);
    $browser->select('type', 'proxy');
    $browser->pause(2000)
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__select')
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__content-wrapper > ul > li:nth-child(4) > span > span')
            ->click('#reports-filter > div:nth-child(3) > div > div > div > div.multiselect__content-wrapper > ul > li:nth-child(9) > span > span')
            ->pause(2000)
            ->press('Export');
    $browser->pause(3000)
            ->clickAudit()                 
            ->pause(3000)
        // ->assertSee('user Export the Request analysis report') 
            ->pause(2000);             

        });

    }


    public function tearDown():void
    {   
        $this->browse(
            function ($browser) {
                $browser->logOutUser();

                if(env('APP_HOME') == "localhost") {
                    $this->user->delete();
                }
            }
        );        
           
        session()->flush();
        parent::tearDown();
    }



}


