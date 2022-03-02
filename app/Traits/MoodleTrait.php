<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 3/29/2020
 * Time: 12:07 AM
 */

namespace App\Traits;


use App\Permission;
use App\PermissionCategory;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait MoodleTrait
{
	use RestTrait;

	private $courseEndpoint = 'http://elearning.thehcmatrix.com/hcm_onboard/index.php?endpoint=training';
	private $enrollementEndpoint = 'http://elearning.thehcmatrix.com/hcm_onboard/index.php?endpoint=onboard';
	private $usersEndpoint = 'http://elearning.thehcmatrix.com/hcm_onboard?endpoint=users';
	private $trackCourseEndPoint = 'http://elearning.thehcmatrix.com/hcm_onboard/?endpoint=track_course'; //&username=1001&courseId=3


	function fetchCourses(){
       return $this->doGet($this->courseEndpoint);
	}

	function fetchEnrolledUsers(){
       return $this->doGet($this->usersEndpoint);
	}

	function enrollUser(Request $request){



		$userId = $request->userId;
        $user = User::find($userId);

        return $this->doPost($this->enrollementEndpoint, function($postData) use ($user){


			 $postData['username'] = urlencode($user->emp_num);
			 $postData['password'] = urlencode($user->password);
			 $postData['email'] = urlencode($user->email);
			 $postData['name'] = urlencode($user->name);

			 return $postData;

        });

	}

	function trackCourse(Request $request){
		$username = $request->get('username');
		$courseId = $request->get('courseId');
		return $this->doGet($this->trackCourseEndPoint . '&username=' . $username . '&courseId=' . $courseId);
	}

	function trackCourseExt($username,$courseId){
//		$username = $request->get('username');
//		$courseId = $request->get('courseId');
		return $this->doGet($this->trackCourseEndPoint . '&username=' . $username . '&courseId=' . $courseId);
	}


	function handlePermissionMigration(){
		$nameCheck = 'E - Learning';
		$permissionCategoryId = null;
		if (PermissionCategory::where('name',$nameCheck)->count() <= 0){

			$obj = new PermissionCategory;
			$obj->name = $nameCheck;
			$obj->save();
			$permissionCategoryId = $obj->id;

		}else{
			$obj = PermissionCategory::where('name',$nameCheck)->first();
			$permissionCategoryId = $obj->id;
		}

		$permissions = [
			[
				'name'=>'Enroll Users',
				'constant'=>'enroll_users_elearning',
				'description'=>'Enroll Users',
				'permission_category_id'=>$permissionCategoryId
			],
			[
				'name'=>'Access E-Learning',
				'constant'=>'access_elearning',
				'description'=>'Access E-Learning',
				'permission_category_id'=>$permissionCategoryId
			],
			[
				'name'=>'Access Courses',
				'constant'=>'access_elearning_courses',
				'description'=>'Access Courses',
				'permission_category_id'=>$permissionCategoryId
			],
			[
				'name'=>'Upload Training Plan',
				'constant'=>'upload_training_plan',
				'description'=>'Upload Course Plan',
				'permission_category_id'=>$permissionCategoryId
			],
			[
				'name'=>'Approve Training Plan',
				'constant'=>'approve_training_plan',
				'description'=>'Approve Training Plan',
				'permission_category_id'=>$permissionCategoryId
			],
			[
				'name'=>'Upload Training Budget',
				'constant'=>'upload_training_budget',
				'description'=>'Upload Training Budget',
				'permission_category_id'=>$permissionCategoryId
			]


		];


		foreach ($permissions as $permission){

			$checkPermConstant = $permission['constant'];
			if (Permission::where('constant',$checkPermConstant)->count() <= 0){
				$obj = new Permission;
				$obj->name = $permission['name'];
				$obj->constant = $permission['constant'];
				$obj->description = $permission['description'];
				$obj->permission_category_id = $permissionCategoryId;
				$obj->save();
			}

		}
	}



}
