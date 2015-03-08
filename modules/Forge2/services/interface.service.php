<?php

if (!function_exists("cmsms")) exit;

interface interfaceService {
	public function getWrapper();
	public function getAllWrapper();
	public function deleteWrapper();
	public function createWrapper();
	public function updateWrapper();

	/*protected function get();
	protected function getAll();
	protected function delete();
	protected function create();
	protected function update();*/

	public function getResponse();
}