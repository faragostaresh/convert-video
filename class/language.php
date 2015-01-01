<?php 

class Language {

	public static function getText($text)
	{
		$list = self::setList();
		if (array_key_exists($text, $list)) {
			return $list[$text];
		}

		return '';
	}

	public static function setList()
	{
		$list = array(
			'headTitle'                   => 'Convert video',
			'headAuthor'                  => 'video',
			'headGenerator'               => 'video',
			'headKeywords'                => 'Convert,video',
			'headDescription'             => 'Convert video',

			'pageTitle'                   => 'Convert video',
			'pageCopyright'               => '© Copyright 2014 - 2015. All rights reserved.',
			'pageLogout'                  => 'Logout',

			'formConvertHelpLable'        => 'Help',
			'formConvertHelpText'         => 'Please upload file on main path and input file name here',
			'formConvertFileLable'        => 'File name',
			'formConvertFilePlaceholder'  => 'Please input file name',
			'formConvertSubmit'           => 'Convert',

			'formLoginEmail'              => 'Email',
			'formLoginPassword'           => 'Password',
			'formLoginSubmit'             => 'Login',

			'messageError'                => 'Error',
			'messageWarning'              => 'Warning',
			'messageSuccess'              => 'Success',
			'messageLoginEmpty'           => 'Email or password is empty, please try again',
			'messageLoginWrongEmail'      => 'Email is not true',
			'messageLoginWrongPassword'   => 'Password is not true',
			'messageLoginSucces'          => 'Your login succes',
			'messageNotLogin'             => 'Please login on website before convert',
			'messageLoginout'             => 'Logout',
			'messageLoginoutText'         => 'You logout success',

			'convertFileName'             => 'File name',
			'convertFilePath'             => 'File path',
			'convertFileSize'             => 'Convert size',
			'convertMediumTitle'          => 'Medium convert finished',
			'convertLowTitle'             => 'Low convert finished',
		);

		return $list;
	}
}
?>