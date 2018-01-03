<?php
class BulkRedir {
	

	function delete(){
		
		global $wpdb;
		$sql = "DELETE FROM bulk_redirects";
		$wpdb->query($sql);
		
	}
	
	function edit($the_link)
	{
		
		global $wpdb;
		$sql = $wpdb->prepare("INSERT INTO bulk_redirects (the_link) VALUES ('%s')", array($the_link));
		$result = $wpdb->query($sql);
	}
	
	function getFields($id){
		
		global $wpdb;
		$sql = $wpdb->prepare("SELECT * FROM bulk_redirects WHERE id = '%s'", array($id));
		$result = $wpdb->query($sql);
		if($result!==0)
		{
			$fields = array();
			foreach($wpdb->get_results($sql) as $row)
			{
				$fields['the_link'] = $row->the_link;
			}
			
			return $fields;
			
		} else {
			
			return false;
		
		}
	}

	function createRedirectsTable()
	{
		global $wpdb;
		$sql = "CREATE TABLE bulk_redirects (id BIGINT(20) PRIMARY KEY AUTO_INCREMENT, the_link TEXT)";
		$result = $wpdb->query($sql);
	}

	function checkForRedirectsTable()
	{
		global $wpdb;
		$sql = "SHOW TABLES LIKE 'bulk_redirects'";
		$result = $wpdb->query($sql);
		if($result==1) {
		
		} else {
		
			$this->createRedirectsTable();

		}

	}
	
	function getAll()
	{
		global $wpdb;
		$this->checkForRedirectsTable();

		$sql = "SELECT * FROM bulk_redirects ORDER by id ASC";
		$result = $wpdb->query($sql);
		if($result!==0){
			
			$id_arr = array();
			foreach($wpdb->get_results($sql) as $row){
				$id_arr[] = $row->id;
			}
			
			return $id_arr;
			
		} else {
			
			return false;
		
		}
	}
	
	function remove($custom_id)
	{
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM bulk_redirects WHERE id = '%s'", array($custom_id));
		$wpdb->query($sql);
	}
	
}

$bulk_redirectsplugin = new BulkRedir();
$GLOBALS['bulk_redirectsplugins'] = $bulk_redirectsplugin;

