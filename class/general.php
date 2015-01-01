<?php 

class General
{
	public static function processing_get($global)
	{
		$get = array();
		// Set part
        $get['part'] = self::cleanVars($global, 'part', 'index', 'string');
        // Set code
        $get['message'] = self::cleanVars($global, 'message', '', 'int');


        return $get;
	}

	public static function processing_post($global)
	{
		$post = array();
		// Set filename
        $post['filename'] = self::cleanVars($global, 'filename', '', 'string');
        // Set email
        $post['email'] = self::cleanVars($global, 'email', '', 'mail');
        // Set password
        $post['password'] = self::cleanVars($global, 'password', '', 'string');

        return $post;
	}

	public static function cleanVars(&$global, $key, $default = '', $type = 'int') 
	{
	    switch ($type) {
	        case 'array':
	            $ret = (isset($global[$key]) && is_array($global[$key])) ? $global[$key] : $default;
	            break;
	        case 'date':
	            $ret = (isset($global[$key])) ? strtotime($global[$key]) : $default;
	            break;
	        case 'string':
	            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
	            break;
		    case 'mail':
	            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_VALIDATE_EMAIL) : $default;
		        break;
		    case 'url':
	            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED) : $default;
		        break;  
	        case 'ip':    
		        $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_VALIDATE_IP) : $default;
	            break; 
	        case 'amp':    
		        $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_FLAG_ENCODE_AMP) : $default;
	            break;
	        case 'text':    
		        $ret = (isset($global[$key])) ? htmlentities($global[$key], ENT_QUOTES, 'UTF-8') : $default;
	            break;     
	        case 'int': default:
	            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
	            break;
	    }
	    if ($ret === false) {
	        return $default;
	    }
	    return $ret;
	}

	public static function redirect($path = '')
	{
		$url = Config::getHost('mainUrl');

		if (!empty($path)) {
			$url = sprintf('%s/%s',$url, $path);
		}

		header('Location: ' . $url);
        exit;
	}

	public function getIp()
	{
		if (!empty($_SERVER["HTTP_CLIENT_IP"]))	{
			//check for ip from share internet
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			// Check for the Proxy User
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}

		return $ip;
	}

	public static function setMessage($id = '')
	{
        // Set form html
        $html = <<<'EOT'
<div class="alert %s" role="alert">
	<h4>%s</h4>
	<p>%s</p>
</div>
EOT;
        $class = 'alert-danger';
        $title = Language::getText('messageError');

        switch ($id) {
        	case 1:
        		$text = Language::getText('messageLoginEmpty');
        		break;

        	case 2:
        		$text = Language::getText('messageLoginWrongEmail');
        		break;
        		
        	case 3:
        		$text = Language::getText('messageLoginWrongPassword');
        		break;

        	case 4:
                $class = 'alert-success';
                $title = Language::getText('messageSuccess');
        		$text = Language::getText('messageLoginSucces');
        		break;
        		
        	case 5:
                $class = 'alert-warning';
                $title = Language::getText('messageWarning');
        		$text = Language::getText('messageNotLogin');
        		break;	

        	case 6:
                $class = 'alert alert-info';
                $title = Language::getText('messageLoginout');
        		$text = Language::getText('messageLoginoutText');
        		break;				
        }

        $message = sprintf(
        	$html,
        	$class,
        	$title,
        	$text
        );

        return $message;
	}

	public static function loginForm()
	{
        // Set form html
        $form = <<<'EOT'
<form class="form-horizontal well" action="%s" method="post">
	<div class="form-group">
		<label for="loginInputEmail" class="col-sm-2 control-label">%s</label>
		<div class="col-sm-10">
		    <input type="email" class="form-control" id="loginInputEmail" name="email">   
    	</div>
	</div>
	<div class="form-group">
		<label for="loginInputPassword" class="col-sm-2 control-label">%s</label>
		<div class="col-sm-10">
		    <input type="password" class="form-control" id="loginInputPassword" name="password">    
    	</div>
	</div>
	<div class="form-group">
    	<div class="col-sm-offset-2 col-sm-10">
    		<button type="submit" class="btn btn-success">%s</button>
    	</div>
	</div>
</form>
EOT;
        // Set action
        $action = sprintf(
        	'%s/index.php?part=doLogin',
        	Config::getHost('mainUrl')
        );
        // Set form elements
        $form = sprintf(
        	$form,
        	$action,
        	Language::getText('formLoginEmail'),
        	Language::getText('formLoginPassword'),
        	Language::getText('formLoginSubmit')
        );
		// return form
		return $form;
	}

	public static function doLogin($post = array())
	{
		// Check is not empty
		if (empty($post['email']) || empty($post['password'])) {
			self::redirect('index.php?part=index&message=1');
		}
        
        // hash password
		$password = md5($post['password']);
        
        // Get system setting
		$system = array(
			'email' => Config::getHost('loginEmail'),
			'pass' => md5(Config::getHost('loginPass')),
		);
        
        // Check email
		if ($system['email'] != $post['email']) {
			self::redirect('index.php?part=index&message=2');
		}
        
        // Check password
		if ($system['pass'] != $password) {
			self::redirect('index.php?part=index&message=3');
		}

		// do login
		$time = time();
		$ip = self::getIp();

		// Set session
		$_SESSION['fg_login'] = 1;
		$_SESSION['fg_ip'] = $ip;
		$_SESSION['fg_time'] = $time;

		// back to index
		self::redirect('index.php?part=index&message=4');
	}

	public static function doLogout()
	{
		session_destroy();
		self::redirect('index.php?part=login&message=6');
	}

	public static function checkLogin()
	{
		if (!empty($_SESSION)) {
			if (isset($_SESSION['fg_login']) &&
				$_SESSION['fg_login'] == 1 &&
				isset($_SESSION['fg_ip']) &&
				!empty($_SESSION['fg_ip']) &&
				isset($_SESSION['fg_time']) &&
				!empty($_SESSION['fg_time'])
            ) {
            	// Get ip
            	$ip = self::getIp();
                // Check
            	if ($ip == $_SESSION['fg_ip']) {
            		return 1;
            	} else {
            		self::redirect('index.php?part=login&message=5');
            	}
			} else {
				self::redirect('index.php?part=login&message=5');
			}
		} else {
			self::redirect('index.php?part=login&message=5');
		}
	}
}
?>