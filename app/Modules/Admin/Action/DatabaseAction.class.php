<?php
/**
 * 后台数据库操作模块
 */
class DatabaseAction extends AdminAction
{
	/**
	 * 数据库备份路径
	 * @var string
	 */
	private $path;
	
	/**
	 * (non-PHPdoc)
	 * @see AdminAction::_initialize()
	 */
	public function _initialize()
	{
		parent::_initialize();
		$this->path = DATA_PATH.'backup/';
	}
	
	/**
	 * 数据库表
	 */
    public function tables(){
		$rs = new Model();
		$list = $rs->query('SHOW TABLES FROM '.C('db_name'));
		$tablearr = array();
        foreach ($list as $key => $val) {
            $tablearr[$key] = current($val);
        }
		$this->assign('list_table',$tablearr);
		$this->display();
    }
    
	/**
	 * 备份数据表
	 */
	public function backuptable(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要备份的数据库表!');
		}
		$filesize = intval($_POST['filesize']);
		if ($filesize<1) {
			$this->error('出错了,请为分卷大小设置一个整数值!');
		}
		$file = $this->path;
		$random = mt_rand(1000, 9999);
		$sql = ''; 
		$p = 1;
		foreach($_POST['ids'] as $table){
			$rs = D(str_replace(C('db_prefix'),'',$table));
			$array = $rs->select();
			$sql.= "TRUNCATE TABLE `$table`;\n";
			foreach($array as $value){
				$sql.= $this->getinsertsql($table, $value);
				if (strlen($sql) >= $filesize*1000) {
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;
					$sql='';
				}
			}
		}
		if(!empty($sql)){
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		$this->assign("jumpUrl",U('Database/backups'));
		$this->success('数据库分卷备份已完成,共分成'.$p.'个sql文件存放!');
    }
    
	/**
	 * 生成SQL备份语句
	 * @param string $table
	 * @param int $row
	 * @return string
	 */
	private function getinsertsql($table, $row){
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) {
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		} 
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql;
	}
	
	/**
	 * 备份列表
	 */
    public function backups()
    {
		$filepath = $this->path.'*.sql';
		$filearr = glob($filepath);
		if (!empty($filearr)) {
			foreach($filearr as $k=>$sqlfile){
				preg_match("/([0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				$backup[$k]['filename'] = basename($sqlfile);
				$backup[$k]['filesize'] = $this->filesize($sqlfile);
				$backup[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
				$backup[$k]['pre']    = $num[1];
				$backup[$k]['number'] = $num[2];
				$backup[$k]['path']   = $this->path;
			}
			$this->assign('list_backup',$backup);
        	$this->display();
		}else{
			$this->assign("jumpUrl", U('Database/tables'));
			$this->error('没有检测到备份文件,请先备份或上传备份文件到'.$this->path);
		}
    }
    
    /**
     * 计算文件大小
     * @param string $file
     */
    private function filesize($file)
    {
    	$size = filesize($file);
    	if($size < 1024*1024){
    		$str = round($size/1024, 2).' K';
    	}else{
    		$str = round($size/(1024*1024), 2).' M';
    	}
    	return $str;
    }
    
	/**
	 * 导入还原
	 */
	public function backin(){
		$rs  = new Model();
		$pre      = $_GET['id'];
		$fileid   = $_GET['fileid'] ? intval($_GET['fileid']) : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = $this->path.$filename;
		if(file_exists($filepath)){
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query) {
				$rs->query(trim($query));
			}
			$params = array();
			$params['id'] = $pre;
			$params['fileid'] = $fileid+1;
			$this->assign("jumpUrl", U('Database/backin', $params));
			$this->success('第'.$fileid.'个备份文件恢复成功,准备恢复下一个,请稍等!');
		}else{
			$this->assign("jumpUrl", U('Database/tables'));
			$this->success('数据库恢复成功!');
		}
	}
	
	/**
	 * 下载备份文件
	 */
	public function download(){
		$filepath = $this->path.$_GET['id'];
		if (file_exists($filepath)) {
			$filename = $filename ? $filename : basename($filepath);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($filepath);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($filepath);
			exit;
		}else{
			$this->error('出错了,没有找到分卷文件!');
		}
	}
	
	/**
	 * 删除分卷文件
	 */
	public function delete()
	{
		$filename = trim($_GET['id']);
		@unlink($this->path.$filename);
		$this->success($filename.'已经删除!');
	}
	
	/**
	 * 批量删除文件
	 */
	public function delall()
	{
		foreach($_POST['ids'] as $value){
			@unlink($this->path.$value);
		}
		$this->success('批量删除分卷文件成功！');
	}
}
?>