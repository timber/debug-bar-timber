<?php

class Debug_Bar_Timber extends Debug_Bar_Panel {

	var $files;
	var $datas;
	var $filenames;
	var $php_files;

	function init() {
		$this->datas = array();
		$this->files = array();
		$this->filenames = array();
		$this->title('Timber');
		add_action('wp_ajax_debug_bar_console', array($this, 'ajax'));
        add_action('timber_loader_render_file', array($this, 'add_file'));
		add_filter('timber_render_file', array($this, 'render_file'));
		add_filter('timber_loader_render_data', array($this, 'render_data'));
		add_filter('timber_calling_php_file', array($this, 'add_php_file'));
    }

    function add_php_file($php_file){
    	$this->php_files[] = $php_file;
    	return $php_file;
    }

    function add_file($file) {
        $this->files[] = $file;
    }

    function render_file($file) {
        $this->filenames[] = $file;
        return $file;
    }

    function render_data($data) {
        $this->datas[] = $data;
        return $data;
    }


	function prerender(){
		$this->set_visible(true);
	}

	function render(){
		$i = 0;
		foreach($this->filenames as $filename){
			echo '<h3 style="display:block; font-size:24px; font-weight:bold; font-family:Consolas, mono; color:#111">'.$filename.'</h3>';
		}
		if (isset($this->php_files) && is_array($this->php_files)){
			foreach($this->php_files as $php_file){
				echo '<h4 style="display:block; font-size:18px; font-weight:bold; font-family:Consolas, mono; color:#AAA">Called from <span style="color:#111">'.$php_file.'</h4>';
			}
		}
		foreach($this->files as $file){
			echo "<p>Timber found template: <code style='font-family:Consolas, mono'>".$this->files[$i]."</code>. Here's the data that you sent: </p>";
			if (count($this->datas) && isset($this->datas[$i])){
				$data = $this->datas[$i];
				echo '<pre style="background-color:#e2e2e2; font-family: Consolas, monospace, mono; white-space:pre">';
				print_r($data);
				echo '</pre>';
			}
			$i++;
		}
	}
}
