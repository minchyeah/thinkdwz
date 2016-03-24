<?php

class CourseClassViewModel extends ViewModel
{
	public $viewFields = array(
			'Courses'=>array('id'=>'course_id','name'=>'course_name','image'=>'coures_image','price'=>'course_price','course_times'),
			'CoursesClass'=>array('id','name','thumb','detail','video','dateline','image','price','course_times'=>'times', '_on'=>'Courses.id=CoursesClass.course_id'),
	);
	
}

?>