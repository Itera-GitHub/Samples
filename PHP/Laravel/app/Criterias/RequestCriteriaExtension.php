<?php


namespace App\Criterias;


use App\Models\SystemCity;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

class RequestCriteriaExtension extends RequestCriteria
{
    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $with = $this->request->get('with', null);
        $withCount = $this->request->get('withCount');
        $whereIn = $this->request->get('whereIn', null);
        $whereHasIn = $this->request->get('whereHasIn', null);
        $whereBetween = $this->request->get('whereBetween', null);
        $whereBetweenOr = $this->request->get('whereBetweenOr', null);
        $whereProximity = $this->request->get('whereProximity', null);
        $whereProximityOr = $this->request->get('whereProximityOr', null);

        if ($with) {
            $with = explode(';', $with);
            $model = $model->with($with);
        }

        if ($withCount) {
            $withCount = explode(';', $withCount);
            $model = $model->withCount($withCount);
        }

        if ($whereIn) {
            $whereInArr = explode(';', $whereIn);
            foreach ($whereInArr as $whereInEl){
                $prepared = explode(':', $whereInEl);
                if(isset($prepared[0]) && isset($prepared[1])){
                    $model = $model->whereIn($prepared[0],explode(',',$prepared[1]));
                }
            }
        }

        //whereHas doesn't support morph
        if($whereHasIn) {
            foreach ($whereHasIn as $whereIn){
                $prepared = explode(':', $whereIn);
                $has = '';
                switch($prepared[0]){
                    case 'cv_candidature.id_offre':
                        $has = 'offers';
                        break;
                    case 'cv_tag_candidat.id_tag':
                        $has = 'tags';
                        break;
                }
                if($has){
                    $model = $model->whereHas($has,function($q) use ($prepared) {
                        $q->whereIn($prepared[0],explode(',',$prepared[1]));
                    });
                }
            }
        }

        if($whereBetween) {
            foreach ($whereBetween as $whereBetweenEl){
                $prepared = explode(':', $whereBetweenEl);
                if(isset($prepared[0]) && isset($prepared[1])) {
                    $model = $model->whereBetween($prepared[0],explode(',',$prepared[1]));
                }
            }
        }

        if ($whereBetweenOr) {
            if (is_array($whereBetweenOr)) {
                foreach ($whereBetweenOr as $whereBetween) {
                    $prepared = explode(':', $whereBetween);
                    if (isset($prepared[0]) && isset($prepared[1])) {
                        $field = $prepared[0];
                        $explodedOr = explode('|', $prepared[1]);
                        $model = $model->where(function ($q) use ($explodedOr, $field) {
                            foreach ($explodedOr as $exploded) {
                                $q->orWhereBetween($field, explode(',', $exploded));
                            }
                        });
                    }
                }
            }
        }

        if($whereProximity) {
            foreach ($whereProximity as $whereProximityElement){
                $prepared = explode(':',$whereProximityElement);
                if(isset($prepared[0]) && isset($prepared[1])){
                    $exploded = explode(',',$prepared[1]);
                    if(isset($exploded[0]) && isset($exploded[1])){
                        $postal = $exploded[0];
                        $distance = $exploded[1];
                        if($requestedCity = SystemCity::where('cpostal','=',$postal)->first()){
                            $nearestCitiesCodes = SystemCity::distance($requestedCity->latitude, $requestedCity->longitude)->having('distance','<=',$distance)->orderBy('distance','ASC')->get()->pluck('cpostal');
                            $model = $model->whereHas('system_city', function($q) use ($nearestCitiesCodes) {
                                $q->whereIn('cpostal',$nearestCitiesCodes);
                            });
                        } else {
                            $model = $model->whereRaw('1 != 1');
                        }
                    }
                }
            }
        }

        if ($whereProximityOr) {
            foreach ($whereProximityOr as $whereProximityElement) {
                $prepared = explode(':', $whereProximityElement);
                if (isset($prepared[0]) && isset($prepared[1])) {
                    $exploded = explode('|', $prepared[1]);
                    $model = $model->where(function ($q) use ($exploded) {
                        foreach ($exploded as $element) {
                            $exploded_element = explode(',', $element);
                            if (isset($exploded_element[0]) && isset($exploded_element[1])) {
                                $postal = $exploded_element[0];
                                $distance = $exploded_element[1];
                                if ($requestedCity = SystemCity::where('cpostal', '=', $postal)->first()) {
                                    $nearestCitiesCodes = SystemCity::distance($requestedCity->latitude,
                                        $requestedCity->longitude)->having('distance', '<=',
                                        $distance)->orderBy('distance', 'ASC')->get()->pluck('cpostal');
                                    $q->orWhereHas('system_city', function ($q) use ($nearestCitiesCodes) {
                                        $q->whereIn('cpostal', $nearestCitiesCodes);
                                    });
                                }
                            }
                        }
                    });

                }
            }
        }
        return $model;
    }

}
