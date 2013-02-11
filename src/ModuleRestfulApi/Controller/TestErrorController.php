<?php

namespace ModuleRestfulApi\Controller;

use ModuleApi\Controller\Util\BaseAbstractRestfulController;

use ModuleModel\Entity\BoxEntity;

use ModuleApi\Response\Type\ItemUpdateResponse;

use ModuleApi\Response\Type\ResultStatusResponse;

use ModuleApi\Response\Type\MessageResultStatusResponse;

use ModuleModel\InputFilter\BoxInputFilter;

use ModuleApi\Response\Type\ItemResponse;

use ModuleApi\Response\Type\ItemsListResponse;

/**
 *
 */
class TestErrorController extends BaseAbstractRestfulController
{
	/**
	 * Return list of resources
	 *
	 * @return array
	 */
	public function getList()
	{
      throw new \Exception("Test Exception");
	}

	/**
	 * Return single resource
	 *
	 * @param mixed $id
	 * @return mixed
	 */
	public function get($id)
	{
	    throw new \Exception("Test Exception");
	}
  
	/**
	 * Create a new resource
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function create($data)
	{
	    throw new \Exception("Test Exception");
	}
  
	/**
	 * Update an existing resource
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return mixed
	 */
	public function update($id, $data)
	{
	    throw new \Exception("Test Exception");
	}

	/**
	 * Delete an existing resource
	 *
	 * @param  mixed $id
	 * @return mixed
	 */
	public function delete($id)
	{
	    throw new \Exception("Test Exception");
	}
}
