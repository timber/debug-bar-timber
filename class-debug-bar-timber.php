
<?php

use Symfony\Component\VarDumper\VarDumper;
use Timber\Timber;

include 'vendor/autoload.php';

/**
 * Class Debug_Bar_Timber
 */
class Debug_Bar_Timber extends Debug_Bar_Panel {

	public array $files;
	public array $datas;
	public array $filenames;
	public array $php_files;

	/**
	 * Initialize the toolbar
	 */
	public function init(): void {
        $this->php_files = array();
        $this->datas = array();
        $this->files = array();
        $this->filenames = array();
        $this->title('Timber');
        add_action('wp_ajax_debug_bar_console', array($this, 'ajax'));

	    $timber = new Timber();
		if(version_compare($timber::$version, '2.0.0', '>=')) {
			add_action( 'timber/loader/render_file', array( $this, 'add_file' ) );
			add_filter( 'timber/render/file', array( $this, 'render_file' ) );
			add_filter( 'timber/loader/render_data', array( $this, 'render_data' ) );
			add_filter( 'timber/calling_php_file', array( $this, 'add_php_file' ) );
			add_filter( 'timber/calling_php_file', array( $this, 'add_php_file' ) );
		}
		else {
			add_action('timber_loader_render_file', array($this, 'add_file'));
			add_filter('timber_render_file', array($this, 'render_file'));
			add_filter('timber_loader_render_data', array($this, 'render_data'));
			add_filter('timber/calling_php_file', array($this, 'add_php_file'));
			add_filter('timber_calling_php_file', array($this, 'add_php_file'));
		}
    }

	/**
	 * @param $php_file
	 *
	 * @return mixed
	 */
	public function add_php_file($php_file){
        $this->php_files[] = $php_file;
        return $php_file;
    }

	/**
	 * @param $file
	 */
	public function add_file($file): void {
        $this->files[] = $file;
    }

	/**
	 * @param $file
	 *
	 * @return mixed
	 */
	public function render_file($file) {
        $this->filenames[] = $file;
        return $file;
    }

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	public function render_data($data) {
        $this->datas[] = $data;
        return $data;
    }


	public function prerender(): void {
        $this->set_visible(true);
    }

	/**
	 * @param mixed ...$vars
	 */
	public function dumpAll(...$vars): void {
		foreach ($vars as $v) {
			VarDumper::dump($v);
		}
	}

	public function render(): void {
        $i = 0;
        foreach($this->filenames as $filename){
            echo '<h3>'.$filename.'</h3>';
        }
        if (isset($this->php_files) && is_array($this->php_files)){
            $this->php_files = array_unique($this->php_files);
            foreach($this->php_files as $php_file){
                echo '<h4>Called from <span>'.$php_file.'</span></h4>';
            }
        }
        foreach($this->files as $file){
            echo "<p>Timber found template: <code>".$file."</code>. Here's the data that you sent: </p>";
            if (count($this->datas) && isset($this->datas[$i])){

                $data = $this->datas[$i];

                if (array_key_exists("post",$data)){

                    echo "<h4>Post Data</h4>";
                    $this->dumpAll($data["post"]);

                    echo "<h4>Author Data</h4>";
	                $this->dumpAll($data["post"]->author);
                }

                echo "<h4>Other Data</h4>";
	            $this->dumpAll($data);

	            echo '<code>{# expected variables:';
	            $dataKeyNames =  array_keys( $data );
	            $i = 0;
	            foreach ( $data as $d ) {
	            	if( is_array($d) ) {
	            		$varName = $dataKeyNames[$i];
	            		$arrayKeynames = array_keys((array) $d[0]);
			            $keynames = '';
			            foreach ( $arrayKeynames as $array_keyname ) {
				            $keynames .= "'" . $array_keyname . "', ";
			            }
			            $keynames = substr($keynames, 0, -2 );
			            echo "<div style='text-indent: .25in'>$varName: array of the form array[0][X] where X is $keynames</div>";
	            	}
	            	$i++;
	            }
	            echo '#}</code>';
            }
            $i++;
        }
    }
}
