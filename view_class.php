<?php
abstract class cache{
        protected $viewhtmlresutl;
        protected $viewname;
        protected $viewblock;
        protected $cachename;
        protected $rawview;
        protected $type;
        public $view_result;

    function getView($viewname, $viewblock,$type) {
    $this->viewname = $viewname;
    $this->viewblock = $viewblock;
    $this->cachename = $this->viewname."class_cache";
    $this->type = $type;	
    }
	
	function checkCached() {
	$cached = cache_get($this->cachename,'cache_views');
	if (isset($cached) && $cached->expire > time()) {
	$this->result_view = $cached->data;
	return $this->cached ='True';
	}
	else {
	
	return $this->cached ='False';
	}
	}

    function cacheView($result) {
	           $serializedsdata = $result; 
                 $nexthour = time()+36000;
                 cache_set($this->cachename,$serializedsdata,'cache_views',$nexthour);	
                    $this->result_view = $result;
					return $this->result_view;        
                 }

}

class cacheview_help  {
function chefheadshots($result) {

       foreach ($result as $row) {
	   $pfid = $row->node_data_field_chef_img_field_chef_img_fid;
	                if (isset($pfid)) {
     $nid = $row->nid;
	 $name = $row->node_title;     
     $file = field_file_load($pfid);
	 $file = $file[filepath].".thumb.jpg";
     $url = url("node/$nid");
	$basepath = base_path();
	 $rurl = "<a href='$url'><img src='$basepath$file'></a>";
	 // $url = l("<img src='$file'>", 'node/$nid', array('html' => 'true'));
	 $cheftype = $row->node_data_field_chefcheftype_field_chefcheftype_value;
     $chefshots[] = array("cheftype"=>$cheftype,"url"=>$rurl,"title"=>$name,"path" => $url);
     }
	 } 
	    return $chefshots;
   }
   
   }

class viewdisplayN extends cache{
        protected $type;
		private static $instance = NULL;
	
	private function _construct() {}
	
	public static function getInstance() {	
	 if (!isset(self::$instance)) {
     
	   self::$instance = new viewdisplayN();
      
	 }
     
		return self::$instance;	
	}
	
		function viewTemplate() {
		$type = $this->type;
		$r = $this->checkCached();
		
			switch ($type) {
				case "chefheadshots":
				     if ($r == 'True'){
					 
					  $t = $this->result_view;
					  }
					  else {
					  $this->rawview = superclean_get_view_result($this->viewname, $this->blockid);
					 
					 $n = new cacheview_help;
					 $result = $n->chefheadshots($this->rawview);
					$t = $this->cacheView($result);						  				 
					  }
					
				break;
				
			}
					
			return $t;
		}
	 
}


?>

