<?php

/**
 * Generates a source to grap the gravatar image from
 * 
 * @param  string $email of the user
 * @param  integer $size of the image from the API
 * @return string The image source for the gravatar image
 */
function gravatar_url($email,$size = 40)
{
	return "http://www.gravatar.com/avatar/".md5($email)."?s=".$size;
}

/**
* Adds the has-error class to form-group having the error
* 
* @param string $key key/name of input field being checked
* @param object $errors just passing the global $errors variable to the function
*/
function set_error($key, $errors)
{
	return $errors->has($key) ? 'has-error' : '';
}
 
/**
* Get the error message and add to a help-block
* 
* @param string $key key/name of input field being checked
* @param object $errors just passing the global $errors variable to the function
*/
function get_error($key, $errors)
{
	return $errors->has($key) ? $errors->first($key, '<span class="help-block">:message</span>'): '';
}
