<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/2/2020
 * Time: 8:51 PM
 */

namespace App\Traits\ViewTraits;


use App\Traits\MoodleTrait;

trait CourseViewTrait
{

	use MoodleTrait;

	function getCourseListingForUser($userId){

		$data = [];
		$data['courses'] = $this->fetchCourses();
		$data['userId'] = $userId;
		//&js-trigger-click-id[]=p-training-header&js-trigger-click-id[]=tr4head


        return view('performance.training.tab_content',$data);
	}

}