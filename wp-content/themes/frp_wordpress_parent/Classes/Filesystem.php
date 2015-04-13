<?php

namespace Frp\WordPress;

class Filesystem {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(&$app){
		$this->app = $app;
	}

	//////////////////////////////////////////////////

    function model_json_saveasfile($array,$filepath){
		/**
    	* save $array as json file to $filepath
    	* if file exists, it will be emptied and overwritten
		 */
	    $fhandle = fopen($filepath,'w');
	    if($fhandle){
		    fwrite($fhandle,json_encode($array));
		    fclose($fhandle);
		    return true;
	    }
	    return false;
    }//model_json_saveasfile

	//////////////////////////////////////////////////

	function last_modified($file){
		/*
		 * get modification date as timestamp
		 * return false if $file is not a file or if 
		 * modification date not available
		 */
		if(!is_file($file)){return false;}
		$filetime = filemtime($file);
		if(!$filetime){$filetime=filectime($file);}
		return $filetime ? $filetime : false;
	}//last_modified

	//////////////////////////////////////////////////

	function last_modified_ago($file){
		/**
		 * get seconds elapsed since modification date
		 * of file (e.g. 120 seconds ago)
		 * return false if $file is not a file or if 
		 * modification date not available
		 */
		if(!is_file($file)){return false;}
		$filetime = filemtime($file);
		if(!$filetime){$filetime=filectime($file);}
		return $filetime ? time()-$filetime : false;
	}//last_modified_ago

	//////////////////////////////////////////////////

	function getFiles($dir,$suffix='') {
		/**
		 * get recursive list of all files in $dir
		 * if $suffix is set, only include files with this suffix in the result list
		 */
		if(!is_dir($dir)){return false;}
		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir),\RecursiveIteratorIterator::CHILD_FIRST);
		$out=array();
	    foreach ($iterator as $path) {
	    	if($path->isFile() && strpos($path->getFileName(), '.')!==0){
			    $out[] = $path->getPathName();
			}
	    }
	    return $out;
	}//getFiles

	//////////////////////////////////////////////////

	function controller_files_delete($path, $extension='', $age=1800){
		/**
		 * delete all files in folder $path (and in subfolders of $path)
		 * if $extension isn't empty, only delete matching files
		 * requires functions get_files() and last_modified_ago() from this class
		 */

		$files = $this->get_files($path,$extension);

		$n=0;

		if(!empty($files)){
			$entries = array();
			foreach($files as $file){
				$filepath = $file->getPathName();
				if($this->last_modified_ago($filepath) > $age){	// e.g. only delete if file is more than 30 minutes old
					if(@unlink($filepath)){
						$n++;
					}
				}
			}
		}
		
		print $n." ".($extension!==''?$extension.' ':'')."files deleted from ".str_replace($_SERVER['DOCUMENT_ROOT'],'',$path)."\r";
		flush();

	}//controller_files_delete

	//////////////////////////////////////////////////

	function controller_file_convertToBase64($path){
		/**
		 * pfad eingeben (relative zu document_root)
		 * falls datei in php geladen werden kann, diese als BASE64-String
		 * enkodieren und zurück geben
		 */
		if($fp = fopen($_SERVER['DOCUMENT_ROOT'].'/'.$path,"rb", 0)){
			$imagedata = getimagesize($path);
			$picture = fread($fp,filesize($path));
			fclose($fp);
			// base64 encode the binary data, then break it
			// into chunks according to RFC 2045 semantics
			$base64 = chunk_split(base64_encode($picture));
			return 'data:'.$imagedata['mime'].';base64,' . $base64;
		}
		return false;
	}//controller_file_convertToBase64


}