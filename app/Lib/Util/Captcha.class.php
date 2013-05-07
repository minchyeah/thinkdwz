<?php
/**
 * 验证码类
 * @author minch <yeah@mich.me>
 * 类用法
 * $captcha = new Captcha();
 * //生成验证码
 * $string = $captcha->rand_string();
 * //显示图片
 * $captcha->show();
 */
class Captcha
{
	/**
	 * @var int 验证码字符数
	 */
	public $length = 4;
	
	/**
	 * @var int 图片宽度
	 */
	public $width = 80;
	
	/**
	 * @var int 图片高度
	 */
	public $height = 30;
	
	/**
	 * @var string 背景颜色
	 */
	public $background = '#FFFFFF';
	
	/**
	 * @var string 验证码字体(字体文件路径)
	 */
	public $font;
	
	/**
	 * @var string 字体颜色
	 */
	public $font_color;
	
	/**
	 * @var number 字体大小
	 */
	public $font_size = 16;
	
	/**
	 * @var string 随机字符集
	 */
	public $charset = 'abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ23456789';
	
	/**
	 * @var string 随机字符串(验证码)
	 */
	private $code;
	
	private $img;
	
	private $x;
	
	/**
	 * 构造函数
	 * @param array|object $config
	 */
	public function __construct($config)
	{
		if (is_object($config) || is_array($config)) {
			foreach ($config as $k=>$v){
				$this->set($k, $v);
			}
		}
		if (!file_exists($this->font)) {
			$this->font = dirname(__FILE__).DIRECTORY_SEPARATOR.'elephant.ttf';
		}
	}
	
	/**
	 * 设置验证码属性
	 * @param string $key 属性项
	 * @param string|int $value 属性值
	 */
	public function set($key, $value)
	{
		$attrs = 'length,width,height,background,font,font_color,font_size,charset';
		if (in_array($key, explode(',', $attrs))) {
			if ($key == 'font' && file_exists($value)) {
				$this->$key = $value;
			}elseif (in_array($key, array('background','font_color'))){
				0 !== strpos($value, '#') && $value = '#'.$value;
				$this->$key = $value;
			}
			else{
				$this->$key = $value;
			}
		}
	}
	
	/**
	 * 生成随机验证码。
	 */
	public function rand_string()
	{
		$str = '';
		$charset_len = strlen($this->charset)-1;
		for ($i=0; $i<$this->length; $i++) {
			$str .= $this->charset[rand(1, $charset_len)];
		}
		$this->code = $str;
		return $this->code;
	}
	
	/**
	 * 生成并显示验证码
	 */
	public function show()
	{
		$code = $this->code ? $this->code : $this->rand_string();
		$this->img = imagecreatetruecolor($this->width, $this->height);
		if (!$this->font_color) {
			$this->font_color = imagecolorallocate($this->img, rand(0,156), rand(0,156), rand(0,156));
		} else {
			$this->font_color = imagecolorallocate($this->img, hexdec(substr($this->font_color, 1,2)), hexdec(substr($this->font_color, 3,2)), hexdec(substr($this->font_color, 5,2)));
		}
		//设置背景色
		$background = imagecolorallocate($this->img,hexdec(substr($this->background, 1,2)),hexdec(substr($this->background, 3,2)),hexdec(substr($this->background, 5,2)));
		//画一个柜形，设置背景颜色。
		imagefilledrectangle($this->img,0, $this->height, $this->width, 0, $background);
		$this->draw_font();
		$this->draw_line();
		$this->output();
	}
	
	/**
	 * 写字
	 */
	private function draw_font()
	{
		$x = $this->width/$this->length;
		for ($i=0; $i<$this->length; $i++) {
			imagettftext($this->img, $this->font_size, rand(-30,30), $x*$i+rand(0,5), $this->height/1.4, $this->font_color, $this->font, $this->code[$i]);
			if($i==0)$this->x=$x*$i+5;
		}
	}
	
	/**
	 * 画线
	 */
	private function draw_line()
	{
		imagesetthickness($this->img, 3);
	    $xpos   = ($this->font_size * 2) + rand(-5, 5);
	    $width  = $this->width / 2.66 + rand(3, 10);
	    $height = $this->font_size * 2.14;
	
	    if ( rand(0,100) % 2 == 0 ) {
	      $start = rand(0,66);
	      $ypos  = $this->height / 2 - rand(10, 30);
	      $xpos += rand(5, 15);
	    } else {
	      $start = rand(180, 246);
	      $ypos  = $this->height / 2 + rand(10, 30);
	    }
	
	    $end = $start + rand(75, 110);
	
	    imagearc($this->img, $xpos, $ypos, $width, $height, $start, $end, $this->font_color);
		
	    if ( rand(1,75) % 2 == 0 ) {
	      $start = rand(45, 111);
	      $ypos  = $this->height / 2 - rand(10, 30);
	      $xpos += rand(5, 15);
	    } else {
	      $start = rand(200, 250);
	      $ypos  = $this->height / 2 + rand(10, 30);
	    }
	
	    $end = $start + rand(75, 100);
	
	    imagearc($this->img, $this->width * .75, $ypos, $width, $height, $start, $end, $this->font_color);
	}
	
	/**
	 * 输出图片
	 */
	private function output() {
		header("content-type:image/png\r\n");
		imagepng($this->img);
		imagedestroy($this->img);
	}
}