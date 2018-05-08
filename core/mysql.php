<?php
// +----------------------------------------------------------------------
// | Author: XiaoCaiMi <info@xiaocaimi.org> <https://www.xiaocaimi.org>
// +----------------------------------------------------------------------
// + MySQL PDO驱动类，通过该类使用PHP的pdo扩展连接处理数据库。
// +----------------------------------------------------------------------

class PdoMySQL
{
    private $db_host;       //数据库主机
    private $db_port;       //数据库端口
    private $db_user;       //数据库登陆名
    private $db_pwd;        //数据库登陆密码
    private $db_name;       //数据库名
    private $tabName;       //表名
    private $pdo=null;      // pdo对象
     //此属性定义要实现连贯操作的方法名
    public $sql = array(
                    "sum" => "",
                    "field" => "",
                    "where" => "",
                    "order" => "",
                    "limit" => "",
                    "group" => "",
                    "having" => "",
                    "empty" => "",
    );

    // 保存最后执行的sql语句
    static $lastSql;

    // 构造函数
    public function __construct($db_host, $db_port, $db_user, $db_pwd, $db_name, $tabName) 
    {
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->db_user = $db_user;
        $this->db_pwd = $db_pwd;
        $this->db_name = $db_name;

        $this->pdo = $this->connect();
        $this->tabName = $tabName;
    }

    /**
     * 连贯操作时,调用field() where() order() limit() group() having()方法且组合成sql语句
     * 此方法为PHP魔术方法,调用类中不存在的方法时就会自动调用此方法
     * @param $methodName 调用不存在的方法时,接收这个方法名称的字符串
     * @param $args 调用不存在的方法时,接收这个方法的参数,以数组形式接收
     */
    public function __call($methodName,$args)
    {
        //把要请求的方法名,统一转为小写
        $methodName = strtolower($methodName);
        //若请求方法名与成员属性数组$sql下标对应上;则将第二个参数,赋值给数组中"下标对应的元素"
        if( array_key_exists($methodName, $this->sql) ){
            if( (empty($args[0]) || (is_string($args[0]) && trim($args[0])==='')) && $args[0] !== 0 ){
				$this->sql[$methodName]="";
			}else{
				$this->sql[$methodName]=$args;
			}
        }else{
            if( DEBUG ) {
                echo '调用类'.get_class($this).'中的'.$methodName.'()方法不存在';
            }else{
                echo 'Error:语法错误';
            }
            exit;
        }
        //返回对象;从而可以继续调用本对象中的方法,形成连贯操作
        return $this;
    }
    
    /**
     *获取数据库连接对象PDO
     */
    private function connect()
    {
        if(is_null($this->pdo)) {
            try{
                $dsn="mysql:host=".$this->db_host.";dbname=".$this->db_name.";port=".$this->port.";charset=utf8";
                //如果你打算用多重查询结果，那么使用mysql时设置PDO的缓冲查询是非常重要的。
                $pdo=new PDO($dsn, $this->db_user, $this->db_pwd, array(PDO::ATTR_PERSISTENT=>true,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }catch(PDOException $e){
                if( DEBUG ) {
                    echo "连接数库失败：".$e->getMessage();
                }else{
                    echo 'Error:语法错误';
                }
            }
        }else{
            return $this->pdo;
        }
    }

    /**
	 * 执行SQL语句的方法
	 * @param	string	$method		SQL语句的类型（find,select,count,add,insert,update,delete）
	 * @param	string	$sql		用户查询的SQL语句
	 * @return	mixed			根据不同的SQL语句返回值
	 */
    private function query($method,$sql)
    {
        $array = explode("::", $method);
        $method =  $array[1];
        $psql = $this->pdo->prepare($sql);  //准备好一个语句

        try{
            $psql->execute();   //执行一个准备好的语句
        }catch( PDOException $e ){
            // 输出sql错误信息
            if( DEBUG ) {
                echo 'Connection failed: '.$e->getMessage().'<br/><br/>'.$sql;
            }else{
                echo 'Error:语法错误';
            }
            exit;
        }

        self::$lastSql = $sql; // 将sql语句存储到静态变量中
        switch($method){
            case 'find': // 返回一条数据
                $data = $psql->fetch(PDO::FETCH_ASSOC);
                break;
            case 'select': // 返回所有数据
                $data = $psql->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'count': // 查询数据库数量
                $row = $psql->fetch(PDO::FETCH_NUM);
                $data = $row[0];
                break;
            case 'sum': // 查询数据库制定内容的和
                $row = $psql->fetch(PDO::FETCH_ASSOC);
                $data = $row['sum'];
                break;
            case 'add': // 添加数据,返回最后插入的id；如果存在则更新数据，返回影响行数
                $rows = $this->pdo->lastInsertId();
                if( $rows ){
                    $data = $rows;
                }else{
                    $data = $psql->rowCount();
                }
                break;
            case 'insert': // 添加数据，返回最后的id
                $data = $this->pdo->lastInsertId();
                break;
            case 'update': // 更新数据，返回最后的id
                $data = $psql->rowCount();  //执行一个数据库，并返回响应行数
                break;
            case 'delete': // 删除数据
                $data = $psql->rowCount();  //执行一个数据库，并返回响应行数
                break;
            case 'setEmpty': // 更新数据，返回最后的id
                $data = $psql->rowCount();  //执行一个数据库，并返回响应行数
                break;
        }
        return $data;
    }


    /**
     * 用此方法拼接成一个find的sql语句
     */
    public function find($id=''){
        // 处理连贯操作
        $fields = $this->sql['field'] != '' ?  $this->sql['field'][0] : '*';

        if( !empty($id) ){
            $where = ' WHERE id='.$id;
        }else{
            if( is_array($this->sql['where'][0]) ){
                $where = ' WHERE ';
                foreach( $this->sql['where'][0] as $key=>$val ){
                    $where .= $key.'="'.$val.'" AND ';
                }
                $where = rtrim($where,' AND ');
            }else{
                $where = $this->sql['where'] != '' ? ' WHERE '.$this->sql['where'][0] : '';
            }
        }

        $order = $this->sql['order'] != '' ?  " ORDER BY {$this->sql['order'][0]}" : '';
       
    
        //按照select语法拼接sql字符串
        $sql='SELECT '.$fields.' FROM '.$this->tabName.$where.$order.' LIMIT 1';
        return $this->query(__METHOD__,$sql);
    }

    /**
     * 用此方法拼接成一个select的sql语句
     */
    public function select(){
        // 处理连贯操作
        $fields = $this->sql['field'] != '' ?  $this->sql['field'][0] : '*';
        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }else{
            $where = $this->sql['where'] != '' ? ' WHERE '.$this->sql['where'][0] : '';
        }
        $order = $this->sql['order'] != '' ?  " ORDER BY {$this->sql['order'][0]}" : '';
        if( count($this->sql["limit"]) == 2 ){
            $limit = $this->sql['limit'] != '' ? ' LIMIT '.$this->sql["limit"][0].','.$this->sql["limit"][1] : '';
        }else{
		    $limit = $this->sql['limit'] != '' ? ' LIMIT '.$this->sql["limit"][0] : '';
        }
		$group = $this->sql['group'] != '' ? ' GROUP BY '.$this->sql['group'][0] : '';
		$having = $this->sql['having'] != '' ? ' HAVING '.$this->sql['having'][0] : '';

        //按照select语法拼接sql字符串
        $sql='SELECT '.$fields.' FROM '.$this->tabName.$where.$group.$having.$order.$limit;
        return $this->query(__METHOD__,$sql);
    }

    /**
     * count 查询数据库中符合条件的数量
     */
    public function count(){
        // 处理连贯操作
        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }else{
            $where = $this->sql['where'] != '' ? ' WHERE '.$this->sql['where'][0] : '';
        }

        //按照select语法拼接sql字符串
        $sql='SELECT COUNT(*) as count FROM '.$this->tabName.$where;
        return $this->query(__METHOD__,$sql);
    }

    /**
     * sum 查询数据库中符合条件的和
     */
    public function sum($field){
        if( $field == '' || !preg_match('/[a-zA-Z]\w+/',$field) ){
            if( DEBUG ) {
                echo '要查询的字段不符合sql字段写法';
            }else{
                echo 'Error:语法错误';
            }
            exit;
        }

        // 处理连贯操作
        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }else{
            $where = $this->sql['where'] != '' ? ' WHERE '.$this->sql['where'][0] : '';
        }

        //按照select语法拼接sql字符串
        $sql='SELECT SUM('.$field.') as sum FROM '.$this->tabName.$where;
        return $this->query(__METHOD__,$sql);
    }

    /**
     * add 添加/更新数据库
     * $array 要添加/更新的数据
     * $filter 是否进行数据过滤，默认1
     */
    public function save($array=null,$filter=1){
        // 如果$array参数为空，则默认是$_POST参数
        $array = is_null($array) ? $_POST : $array;
        // 进行数据过滤
        $array = $this->check($array,$filter);

        // 判断提交的数据中是否包含id
        if( in_array('id',array_keys($array)) ){
            $where = ' WHERE id='.$array['id'];
            unset($array['id']);

            // 组合set语句
            $set = '';
            foreach( $array as $key=>$val ){
                $set .= $key.'="'.$val.'",'; 
            }
            $set = rtrim($set,',');

            //按照select语法拼接sql字符串
            $sql='UPDATE '.$this->tabName.' SET '.$set.$where;
        }else{
            // value要包含引号
            $str = join(",",$array);
            $value = '"'.str_replace(',','","',$str).'"';
            //按照select语法拼接sql字符串
            $sql='INSERT INTO '.$this->tabName.'('.implode(',', array_keys($array)).') VALUES ('.$value.')';
        }
        return $this->query(__METHOD__,$sql);
    }

    /**
     * insert 添加数据库
     * $array 要添加的数据
     * $filter 是否进行数据过滤，默认1
     */
    public function insert($array=null,$filter=1){
        // 如果$array参数为空，则默认是$_POST参数
        $array = is_null($array) ? $_POST : $array;
        // 进行数据过滤
        $array = $this->check($array,$filter);

        // value要包含引号
        //$str = join(",",$array);
        //$value = '"'.str_replace(',','","',$str).'"';
        
        foreach($array as $key=>$val){
            $keyName .= "{$key},";
            $valName .= "'{$val}',";
        }
        //按照select语法拼接sql字符串
        $sql='INSERT INTO '.$this->tabName.'('.rtrim($keyName,',').') VALUES ('.rtrim($valName,',').')';
        return $this->query(__METHOD__,$sql);
    }

    /**
     * update 更新数据库
     * $array 要添加的数据
     * $filter 是否进行数据过滤，默认1
     */
    public function update($array=null,$filter=1){
        // 如果$array参数为空，则默认是$_POST参数
        $array = is_null($array) ? $_POST : $array;
        // 进行数据过滤
        $array = $this->check($array,$filter);

        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }else{
            $where = $this->sql['where'] != '' ? ' WHERE '.$this->sql['where'][0] : '';
        }

        // 组合set语句
        $set = '';
        foreach( $array as $key=>$val ){
            if( strpos($val,'`') !== false ){
                $set .= $key.'='.$val.','; 
            }else{
                $set .= $key.'="'.$val.'",'; 
            }
        }
        $set = rtrim($set,',');

        //按照select语法拼接sql字符串
        $sql='UPDATE '.$this->tabName.' SET '.$set.$where;
        return $this->query(__METHOD__,$sql);
    }

    /**
     * delete 删除数据库
     */
    public function delete(){
        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }elseif( $this->sql['where'][0] != '' ){
            $where = ' WHERE '.$this->sql['where'][0];
        }else{
            $args=func_get_args();
            if( $args && $args[0] > 0 ){
                $where = ' WHERE id='.$args[0];
            }
        }

        //按照select语法拼接sql字符串
        $sql='DELETE FROM '.$this->tabName.$where;
        return $this->query(__METHOD__,$sql);
    }

    /**
     * empty 设置某字段为空
     */
    public function setEmpty($field){
        if( is_array($this->sql['where'][0]) ){
            $where = ' WHERE ';
            foreach( $this->sql['where'][0] as $key=>$val ){
                $where .= $key.'="'.$val.'" AND ';
            }
            $where = rtrim($where,' AND ');
        }elseif( $this->sql['where'][0] != '' ){
            $where = ' WHERE '.$this->sql['where'][0];
        }else{
            $args=func_get_args();
            if( $args && $args[0] > 0 ){
                $where = ' WHERE id='.$args[0];
            }
        }

        // 组合set语句
        $set = $field."=''";

        //按照select语法拼接sql字符串
        $sql='UPDATE '.$this->tabName.' SET '.$set.$where;
        return $this->query(__METHOD__,$sql);
    }

    // 数据过滤操作
    private function check($array, $filter){
        // 获取表中所有字段名
        $psql=$this->pdo->prepare('desc '.$this->tabName);
        $psql->execute();
        $fields=array();
        while($row = $psql->fetch(PDO::FETCH_ASSOC)){
            $fields[] = strtolower($row['Field']);
        }

        $arr=array();
    
        foreach($array as $key=>$value){
            // 键值转换位小写
            $key=strtolower($key);
            // 判断键值是否是数据表中含有的字段
            if(in_array($key, $fields) && $value !== ''){
                // 判断是否过滤字段
                if($filter && !empty($filter)){
                    $arr[$key]=stripslashes(htmlspecialchars($value));
                }else{
                    $arr[$key]=$value;
                }
            }   
        }
        return $arr;
    }

    
    public function getVersion(){
        // 获取Mysql版本信息
        $version = $this->query("::select","select version() as ver");
        return $version[0]['ver'];
        
    }

    public function getLastSql(){
        // 读取最后执行的sql语句
        return self::$lastSql;
    }
}
