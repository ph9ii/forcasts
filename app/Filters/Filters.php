<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{	
	protected $request, $builder;

	protected $filters = [];

	/**
	* ThreadFilters constructor
	* 
	* @param Request $request
	*/
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	* Apply function
	* @param $builder
	* @return collection
	*/
	public function apply($builder)
	{
		$this->builder = $builder;
		
		// dd($this->request->intersect($this->filters));

		$this->getFilters()
			->filter(function ($filter) {
				return method_exists($this, $filter);
			})
			->each(function ($filter, $value) {
				$this->$filter($value);
			});

		// foreach($this->getFilters() as $filter => $value) {
		// 	if (method_exists($this, $filter)) { 
		// 		$this->$filter($value);
		// 	}
		// }

        return $this->builder;
	}

	protected function getFilters()
	{
		return collect($this->request->intersect($this->filters))->flip();
	}
}





