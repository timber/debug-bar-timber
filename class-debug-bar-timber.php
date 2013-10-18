<?php

class Debug_Bar_Timber extends Debug_Bar_Panel {

	var $files;
	var $datas;
	var $filenames;

	function init() {
		$this->datas = array();
		$this->files = array();
		$this->filenames = array();
		$this->title('Timber');
		add_action('wp_ajax_debug_bar_console', array($this, 'ajax'));
		add_action('timber_loader_render_file', function($filepath){
			$this->files[] = $filepath;
		});
		add_filter('timber_render_file', function($file){
			$this->filenames[] = $file;
			return $file;
		});
		add_filter('timber_render_data', function($data){
			$this->datas[] = $data;
			return $data;
		});
	}

	function prerender(){
		$this->set_visible(true);
	}

	function render($file){
		$i = 0;
		foreach($this->filenames as $file){
			echo '<h3 style="display:block; font-size:24px; font-weight:bold; font-family:Consolas, mono">'.$file.'</h3>';
			echo "<p>Timber found template: <span style='font-family:Consolas, mono'>".$this->files[$i]."</span>. Here's the data that you sent: </p>";
			if (count($this->datas) && isset($this->datas[$i])){
				$data = $this->datas[$i];
				echo '<pre style="background-color:#e2e2e2; font-family: Consolas, monospace, mono">';
				print_r($data);
				echo '</pre>';
			}
			$i++;
		}
	}
}