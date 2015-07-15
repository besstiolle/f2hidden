<?php

if (!function_exists("cmsms")) exit;

class file_avatarService extends abstractService implements interfaceService {
	
	protected $serviceName = 'file_avatar';

	protected $jsonBlock = 'avatars';

	protected $currentEntity;

	private $type = EnumForgeFile::Avatar; 

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

			//Find images in directories
			$config = cmsms()->GetConfig();
			$dir = $config['root_path'].'/uploads/projects/'.$this->params['sid'].'/avatar/';
			$url = $config['root_url'].'/uploads/projects/'.$this->params['sid'].'/avatar/';
			$pattern = '/\.(gif|jpe?g|png)$/i';
			$files = file_avatarService::getFilesInDir($dir, $pattern);
			$count = ''.count($files);

			$entityVals = array();
			foreach ($files as $file) {
				$entityVals[] = array(
					'name' => $file,
					'url' => $url.$file,
					);
			}
			
		} else {

			//Select by example
			$example = new OrmExample();
			if(!empty($this->params['sid']) ) {
				$example->addCriteria('id_related', OrmTypeCriteria::$EQ, array($this->params['sid']));
			}
			$example->addCriteria('type',OrmTypeCriteria::$EQ, array($this->type));
			
			$entities = OrmCore::findByExample($this->currentEntity, $example);

			//counter
			$count = OrmCore::selectCountByExample($this->currentEntity, 
												$example);
			$entityVals = OrmUtils::entitiesToAbsoluteArray($entities);
		}

		$this->response->addContent($this->jsonBlock, $entityVals);
		$this->response->addContent('count', $count);

		return $this->response;
	}

	function delete(){

		//Find images in directories
		$config = cmsms()->GetConfig();
		$file = $config['root_path'].'/uploads/projects/'.$this->params['sid'].'/avatar/'.$this->params['filename'];
		if(!file_exists($file) ){
			$this->response->setCode(404);
			$this->response->setMessage("Not Found");
		}

		unlink($file);

		return $this->response;

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