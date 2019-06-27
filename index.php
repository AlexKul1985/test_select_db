<?php 
  class QuerySelect{
  
    private $query = '';
    private $table_name = '';
    
    public function __construct($table_name){
	$this -> table_name = $table_name;
    }
    
    public function get(){
	return $this -> query;
    }
    
    private function select($array_fields = []){
	if(empty($array_fields)){
	    throw new Exception('Массив не должен быть пустым');
	}
	$fields = '';
	
	for($i = 0; $i < count($array_fields); $i++){
	    if($i > 0 && $i <= count($array_fields) - 1){
		$fields .= ', ';
	    }
	    $fields .= '`'.$array_fields[$i].'`';

	}
	return 'SELECT '.$fields.' FROM '.$this -> table_name;
    }

    public function all(){
	return $this -> select(['*']);
    }
    
    public function where($field, $separator, $flag = false){
	
	$query = ' WHERE '.$field.' '.$separator.' ?';
	$return =  empty($this -> query)  ? $query : (($this -> query .= $query) && $flag) ? $query : $this;
	return $return;
    }
    
    public function find($fields_search){
	$this -> query = $this -> select($fields_search);
	return $this;
    }
    
    public function findWhere($fields_search,$field_where, $separator){
	$this -> query = '';
	return $this -> select($fields_search).$this -> where($field_where,$separator, true);
    }
    
    public function whereAnd($double_array_fields_separators){
      
	$whereAnd = '';
	for($i = 0; $i < count($double_array_fields_separators); $i++){
	    [$field, $separator] = $double_array_fields_separators[$i];
	    if($i == 0) {
		if(!empty($this -> query))
		    $this -> where($field, $separator, true);
		else{
		    
		    $whereAnd .= $this -> where($field, $separator);
		}
	    }
	    else {
		$whereAnd .= ' AND '.$field.' '.$separator.' ?';
	    }
	}
	
	$return = empty($this -> query) ? $whereAnd : (($this -> query .= $whereAnd) ? $this : true) ;
	
	
	return $return;
	
    }

    public function findWhereAnd($fields_search, $double_array_fields_separators)
    {
	$this -> query = '';
	
	return $this -> select($fields_search).$this -> whereAnd($double_array_fields_separators);

    }

	
  }

  $q = new QuerySelect('users');
  
  echo $q -> find(
  [
  'id',
  'name',
  'age'
  ]
  )-> where(
  
  'id',
  '='
 ) -> get();
  
  
  echo PHP_EOL;