<?php

namespace WJ\Wobe;

class Wobe
{
    protected $countries;

	protected $world;

	public function __construct()
	{
		$this->countries = config('wobe.countries');
		$this->world     = config('wobe.all');
	}

	public function getCountries()
	{
		return $this->countries;
	}

	public function getWorld()
	{
		return $this->world;
	}

	public function getStates($country = null, $showCities = false)
	{
		$country = $country ?: 'VE';

		$countryId = $this->countries[$country][0];

		$states = $this->world[$countryId]['states'];

		if ($showCities) {
			return $states;
		}

		$statesWithoutCities = [];

		$collection = collect($states);

        return $collection->transform(function ($item, $key) {
            return $this->stateToObject($item['data']);
        });
	}

	public function getIdsFromCity($cityCode)
	{
		return explode('-', $cityCode);	
	}

	public function getIdsFromState($stateCode)
	{
		return explode('-', $stateCode);	
	}

	public function city($cityCode)
    {
        list($countryId, $stateId, $cityId) = explode('-', $cityCode);

        $city = $this->world[$countryId]['states'][$stateId]['cities'][$cityId];

        return $this->cityToObject($city);
    }

    public function stateOfCity($cityCode)
    {
        list($countryId, $stateId, $cityId) = explode('-', $cityCode);

        return $this->world[$countryId]['states'][$stateId]['data'];
    }

    public function countryOfCity($cityCode)
    {
        list($countryId, $stateId, $cityId) = explode('-', $cityCode);

        return $this->world[$countryId]['data'];
    }

    public function state($stateCode)
    {
        list($countryId, $stateId) = explode('-', $stateCode);

        $state = $this->world[$countryId]['states'][$stateId]['data'];

        return $this->stateToObject($state);
    }

    public function countryOfState($stateCode)
    {
        list($countryId, $stateId) = explode('-', $stateCode);

        return $this->world[$countryId]['data'];
    }

    public function country($countryCode)
    {
        return $this->world[$countryCode]['data'];
    }

    public function statesOfCountry($countryCode)
    {
        return $this->world[$countryCode]['states'];
    }

    public function getCities($stateCode)
    {
        list($countryId, $stateId) = explode('-', $stateCode);

        $cities = $this->world[$countryId]['states'][$stateId]['cities'];

        $collection = collect($cities);

        return $collection->transform(function ($item, $key) {
            return $this->cityToObject($item);
        });
    }

    public static function getIdsOfCitiesFromContry($country = null)
    {
    	$country = $country ?: 'VE';

    	$countryId = config('wobe.countries')[$country][0];

		$states = config('wobe.all')[$countryId]['states'];

    	$cities = [];

		foreach ($states as $key => $state) {

			foreach ($state['cities'] as $city) {
				array_push($cities, $city[0]);
			} 
		}

		return $cities;
    }

    public function getIdsOfCitiesFromState($stateCode)
    {
        $cities = $this->getCities($stateCode);

        $citiesIds = [];

		foreach ($cities as $city) {
			array_push($citiesIds, $city->id);
		}

		return $citiesIds;
    }

    protected function cityToObject($array)
    {
        $city['id'] = $array[0];
        $city['name'] = $array[1];

        return $this->toObject($city);
    }

    protected function stateToObject($array)
    {
        $state['id'] = $array[0];
        $state['name'] = $array[1];

        return $this->toObject($state);
    }

    protected function toObject($array)
    {
        return json_decode(json_encode($array), false);
    }
}