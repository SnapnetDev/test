<?php

namespace App\Providers;

use App\Interfaces\AnniversaryInterface;
use App\Repositories\AnniversaryRepository;
use App\Services\AutoPermissionMigrations\PermissionManager;
use App\Traits\MoodleTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
//        $this->app->bind(AnniversaryInterface::class, AnniversaryRepository::class);
//        view()->share('courses',$this->fetchCourses());
	    Blade::directive('usercan', function($expression){
	    	//@if(Auth::user()->role->permissions->contains('constant', 'run_payroll'))
		    return '<?php if(Auth::user()->role->permissions->contains(\'constant\', \'' . $expression . '\')) { ?>';
	    });

	    Blade::directive('endusercan', function($expression){
	    	return '<?php } ?>';
	    });

	    view()->share('selected',function($expr){
	        if ($expr){
	            return ' selected ';
            }
	        return  '';
        });





        view()->share('can_access_onboarding_settings',function(){
            return PermissionManager::hasPermission('on_board_settings');
        });

        view()->share('can_access_onboarding_employess',function(){
            return PermissionManager::hasPermission('on_board_employee');
        });

    }


}
