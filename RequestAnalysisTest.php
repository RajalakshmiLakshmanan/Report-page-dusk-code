<?php

namespace Tests\Browser\Feature\Report;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\RequestAnalysisPage;

class RequestAnalysisTest extends DuskTestCase
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
                ->clickLink('Request Analysis','a')
                ->visit(new RequestAnalysisPage);
            }
        );
    }

    

   /**
     * Test if the user can filter and export the Request Analysis Report in reports page
     *
     * @return void
     */
    public function test_if_user_can_filter_and_export_the_Request_Analysis_Report()
    {
        $this->browse(function (Browser $browser) {
    
    $browser->clickFilterOption()
            ->pause(3000)
            ->clickExportOption()
            ->pause(3000)
            ->back()
            ->visit(new HomePage)
            ->pause(2000)
            ->clickAudit()                 
            ->pause(3000);
         // ->assertSee('user fliter the Request analysis report') 
              

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

