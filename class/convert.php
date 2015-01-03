<?php 

class Convert
{
	public static function convertForm()
	{
        // Set form html
        $html = <<<'EOT'
<form class="form-horizontal well" action="%s" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">%s</label>
		<div class="col-sm-10">
			<p class="form-control-static">%s</p>
		</div>
	</div>
	<div class="form-group">
		<label for="inputFileName" class="col-sm-2 control-label">%s</label>
		<div class="col-sm-10">
		    <div class="input-group">
		        <div class="input-group-addon">%s/</div>
    		    <input type="text" class="form-control" id="inputFileName" name="filename" placeholder="%s">
    		</div>    
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
        	'%s/index.php?part=convert',
        	Config::getHost('mainUrl')
        );
        // Set form elements
        $form = sprintf(
        	$html,
        	$action,
        	Language::getText('formConvertHelpLable'),
        	Language::getText('formConvertHelpText'),
        	Language::getText('formConvertFileLable'),
        	Config::getHost('sourcePath'),
        	Language::getText('formConvertFilePlaceholder'),
        	Language::getText('formConvertSubmit')
        );
		// return form
		return $form;
	}

	public static function doing($video = '')
	{
		// Check file set
		if (empty($video)) {
			General::redirect('index.php?part=index&message=7');
		}
        
        // Set file path
        $file = sprintf('%s/%s',
        	Config::getHost('sourcePath'),
        	$video
        );
        
        // Check file exist
        if (!file_exists($file)) {
			General::redirect('index.php?part=index&message=8');
        }

        // Check main file is MP4
        if ('mp4' != pathinfo($video, PATHINFO_EXTENSION)) {
			General::redirect('index.php?part=index&message=9');
        }
        
        // Set convert
        $_SESSION['fg_convert_do'] = 1;

        // Do convert
        $result = array();
        $result['medium'] = self::startConvert($video, 'medium');
        $result['low'] = self::startConvert($video, 'low');
        
        // Set session
        $_SESSION['fg_convert_do'] = 0;
        $_SESSION['fg_convert_result'] = $result;
        
        // redirect
        General::redirect('index.php?part=finish');
	}

	public static function startConvert($video, $quality)
	{
        // ffmpeg -i input.mp4 -s 640x480 -acodec libfaac -vcodec libx264 -vpre max -r 30 -maxrate 1000 -ab 128000 -ar 44000 -f mp4 output.mp4
        // Set name
        $name = pathinfo($video, PATHINFO_FILENAME);
        // Setting
        switch ($quality) {
        	case 'medium':
        	    $name = sprintf('%s-medium.mp4', $name);
        		$size = Config::getConvertConfig('size_medium');
                $targetBitrate = Config::getConvertConfig('targetBitrate_medium');
                $videoBitrateTolerance = Config::getConvertConfig('videoBitrateTolerance_medium');
                $audioBitRate = Config::getConvertConfig('audioBitRate_medium');
        		break;
        	
        	case 'low':
        	    $name = sprintf('%s-low.mp4', $name);
        		$size = Config::getConvertConfig('size_low');
                $targetBitrate = Config::getConvertConfig('targetBitrate_low');
                $videoBitrateTolerance = Config::getConvertConfig('videoBitrateTolerance_low');
                $audioBitRate = Config::getConvertConfig('audioBitRate_low');
        		break;
        }
        // Set file path
        $input = sprintf('%s/%s', Config::getHost('sourcePath'), $video);
        $output = sprintf('%s/%s', Config::getHost('sourcePath'), $name);
        // Set command
        //$command = 'ffmpeg -i %s -s %s -acodec libfaac -vcodec libx264 -vpre max -r 30 -maxrate 1000 -ab 128000 -ar 44000 -f mp4 %s';
        //$command = "ffmpeg -i %s -s %s -aspect 16:9 -r 25 -b 360k -bt 416k -vcodec libx264 -pass 1 -vpre fastfirstpass -an %s && ffmpeg -y -i %s -s %s -aspect 16:9 -r 25 -b 360k -bt 416k -vcodec libx264 -pass 2 -vpre hq -acodec libfaac -ac 1 -ar 22050 -ab 64k %s";
        /* $command = "ffmpeg -i %s -s 320x240 -aspect 16:9 -r 25 -b %s -bt %s -vcodec libx264 -pass 1 -vpre fastfirstpass -an %s && ffmpeg -y -i %s -s 320x240 -aspect 16:9 -r 25 -b %s -bt %s -vcodec libx264 -pass 2 -vpre hq -acodec libfaac -ac 1 -ar 22050 -ab %s %s";
        $command = sprintf(
        	$command, 
        	$input,
            $aaa1,
            $aaa2,
        	$output,
            $input,
            $aaa1,
            $aaa2,
            $aaa3,
            $output
        ); */
        $command = "ffmpeg -i %s -s %s -aspect 16:9 -r 25 -b %s -bt %s -vcodec libx264 -pass 2 -vpre fastfirstpass -acodec libfaac -ac 1 -ar 22050 -ab %s %s";
        $command = sprintf(
            $command, 
            $input,
            $size,
            $targetBitrate,
            $videoBitrateTolerance,
            $audioBitRate,
            $output
        );
        // do convert
		exec($command);
		// return
		return array(
			'name'     => $name,
			'input'    => $input,
			'output'   => $output,
			'size'     => $size,
			'command' => $command,
		);
	}

	public static function convertMessage()
	{
        // Check
        if (isset($_SESSION['fg_convert_result']) && !empty($_SESSION['fg_convert_result'])) {
        	$result = $_SESSION['fg_convert_result'];
        	unset($_SESSION['fg_convert_result']);
        } else {
        	return '';
        	exit;
        }

        // Set html
        $html = <<<'EOT'
<div class="alert alert-success">
    <h4>%s</h4>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p>%s</p>
</div>
<div class="alert alert-success">
    <h4>%s</h4>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p><strong>%s</strong> : <span>%s</span></p>
    <p>%s</p>
</div>
EOT;

        // Set message
        $message = sprintf(
        	$html,
        	Language::getText('convertMediumTitle'),
        	Language::getText('convertFileName'),
        	$result['medium']['name'],
        	Language::getText('convertFilePath'),
        	$result['medium']['output'],
        	Language::getText('convertFileSize'),
        	$result['medium']['size'],
        	$result['medium']['command'],
        	Language::getText('convertLowTitle'),
        	Language::getText('convertFileName'),
        	$result['low']['name'],
        	Language::getText('convertFilePath'),
        	$result['low']['output'],
        	Language::getText('convertFileSize'),
        	$result['low']['size'],
        	$result['low']['command']
        );
        return $message;
	}
}
?>