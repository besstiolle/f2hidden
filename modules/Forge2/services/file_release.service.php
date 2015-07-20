<?php

if (!function_exists("cmsms")) exit;

class file_releaseService extends abstractService implements interfaceService {
	
	protected $serviceName = 'file_release';

	protected $jsonBlock = 'releases';

	protected $currentEntity;

	private $type = EnumForgeFile::Release; 

	public function __construct($path, $params){
		//Create exclusivly
		ApiRequest::allowMethods(ApiRequest::$PUT, ApiRequest::$GET, ApiRequest::$DELETE);

		$this->initResponse($path, $params);

		$this->currentEntity = new ForgeFile();
	}

	function get(){

		//Select by example
		$example = new OrmExample();
		$example->addCriteria('id', OrmTypeCriteria::$EQ, array($this->params['sid']));

		//We don't need the sid anymore
		unset($this->params['sid']);

		$entities = OrmCore::findByExample($this->currentEntity, 
											$example, 
											null, 
											new OrmLimit(0, 10));

		if(empty($entities)){
			$this->response->setCode(404); 
		}

		$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);

		$this->response->addContent($this->jsonBlock, $entityVals);
		return $this->response;
	}

	function getAll(){

		if(!isset($this->params['onTransfert']) || $this->params['onTransfert'] == 0) {

			//Get the projectId
			$entity = OrmCore::findById(new Release(), $this->params['sid']);
			$projectId = $entity->get('package_id')->get('project_id');

			//Find images in directories
			$config = cmsms()->GetConfig();
			$dir = $config['root_path'].'/uploads/projects/'.$projectId.'/release/'.$this->params['sid'].'/file/';
			$url = $config['root_url'].'/uploads/projects/'.$projectId.'/release/'.$this->params['sid'].'/file/';
			$pattern = '/\.(tar|xml|zip)$/i';
			$files = $entity->get('files');
			

			$entityVals = array();
			foreach ($files as $file) {

				$filename = $file->get('filename');
				$arr = array();
				if(!file_exists($dir.$filename)){
				//	error_log("File ".$dir.$filename." is not found");
					continue;
				}

				/*$conf = array();
				//Load configuration of the release filename if it exists
				if(file_exists($dir.$filename.'.ini')){
					$conf = parse_ini_file($dir.$filename.'.ini');
				}*/

				$arr['name'] = $filename;
				$arr['url'] = $url.$filename;
				$arr['content_type'] = $file->get('content_type');
				$arr['size'] = $file->get('size');
				$arr['downloads'] = $file->get('downloads');
			
				$entityVals[$file] = $arr;
			}
			$count = ''.count($entityVals);

		} else {

			//Select by example
			$example = new OrmExample();
			if(!empty($this->params['sid']) ) {
				$example->addCriteria('id_related', OrmTypeCriteria::$EQ, array($this->params['sid']));
			}
			$example->addCriteria('type',OrmTypeCriteria::$EQ, array($this->type));
			
			$entities = OrmCore::findByExample($this->currentEntity, $example);

			//counter
			$count = OrmCore::selectCountByExample($this->currentEntity, $example);
			$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);
		}

		$this->response->addContent($this->jsonBlock, $entityVals);
		$this->response->addContent('count', $count);

		return $this->response;
	}

	function delete(){

		//
	}

	function create(){
		
		$entityVals = array();

		$files = $this->params['files'];
		foreach ($files as $name => $info) {
			//TODO tester regex url
			if(!array_key_exists('url', $info)){
				continue;
			}
			if(!array_key_exists('md5', $info)){
				continue;
			}

			$entity = new ForgeFile();
			$entity->set('name', $name);
			$entity->set('url', $info['url']);
			$entity->set('id_related', $this->params['sid']);
			$entity->set('type', $this->type);
			$entity->set('md5', $info['md5']);
			$entity->set('created_at',time());
			
			//Save the entity
			$entity = $entity->save();

			$entityVals[] = OrmUtils::entityToAbsoluteArray($entity);
		}


		$this->response->addContent('info', 'entity created with success');
		$this->response->addContent($this->jsonBlock, $entityVals);

		return $this->response;
	}

	function update(){

		//
	}
	
	/**
     * Will return the list of file in the directory wich match the pattern.
     *
     * @param directory the directory
     * @param pattern the pattern
     **/
    public static function getFilesInDir($directory, $pattern){
        $files = array();
        if(!is_dir($directory)){
            return null;
        }
        if ($handle = opendir($directory)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && !is_dir($directory.'/'.$entry) && preg_match( $pattern , $entry)) {
                   $files[] = $entry;
                }
            }
            closedir($handle);
        }
        return $files;
    }
}


?>