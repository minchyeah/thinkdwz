<?php

class CourseClassViewModel extends ViewModel
{
	public $viewFields = array(
			'Courses'=>array('id'=>'course_id','name'=>'course_name','image','price','course_times'),
			'CoursesClass'=>array('id','name','thumb','detail','video','dateline', '_on'=>'Courses.id=CoursesClass.course_id'),
	);
	
}

?>