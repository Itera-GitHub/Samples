<?php


namespace App\Criterias;


use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

class CandidatesFulltextSearchRequestCriteria extends RequestCriteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        $fulltextPattern = '/^((?:[+\-]?(?:(?:[^+\s\-\>\<\(\)\~\*\:\"\&\|]{2,}\*?|(?:"(?:[^"\s]{2,}[ ]*)+"))|\((?:(?:[^+\s\-\>\<\(\)\~\*\:\"\&\|]{2,}\*?|(?:"(?:[^"\s]{2,}[ ]*)+"))[ ]*)+\))(?:[ ]+|$))+)$/';
        $keywords = $this->request->get('fulltext', null);
        $searchFields = method_exists($repository,'getFulltextSearchableFields') ? $repository->getFulltextSearchableFields() : [];
        if ($keywords && count($searchFields)) {
            if(preg_match($fulltextPattern,$keywords)){
                $keywords = addslashes($keywords);
                $searchFieldsStr = count($searchFields) ==1 ? $model->getTable().'.'.$searchFields[0] : $model->getTable().'.'.implode(','.$model->getTable().'.',$searchFields);
                if(config('fulltext-search.candidates.use_joins',false)){
                    $joins = config('fulltext-search.candidates.joins',[]);
                    $selectFields = '';
                    $joinConditions = [];
                    $where = '';
                    $orderBy = '';
                    $bindings = [$keywords];
                    foreach($joins as $k=>$join){
                        $joinRepo = app($join['repository_class']);
                        $joinSearchFields = method_exists($joinRepo,'getFulltextSearchableFields') ? $joinRepo->getFulltextSearchableFields() : [];
                        if($joinSearchFields) {
                            $imploded = count($joinSearchFields) == 1 ? $join['table'].'.'.$joinSearchFields[0] : $join['table'].'.'.implode(','.$join['table'].'.',$joinSearchFields);
                            $newScore = 'MATCH('.$imploded.') AGAINST (? IN BOOLEAN MODE)';
                            $selectFields = $selectFields . ", $newScore as join_score_$k";
                            $orderBy = $orderBy . "$newScore,";
                            array_push($joinConditions,$join);
                            $where = $where . ' OR '.$newScore;
                            array_push($bindings,$keywords);
                        }
                    }
                    if($selectFields && $orderBy){
                        $table_name = $model->getTable();
                        $orderBy = 'main_score,'.rtrim($orderBy,',').' DESC';
                        $selectFields = $table_name.'.*, MATCH ('.$searchFieldsStr.') AGAINST (? IN BOOLEAN MODE) as main_score';
                        $where = '(MATCH ('.$searchFieldsStr.') AGAINST (? IN BOOLEAN MODE)'.$where.')';
                        $model = $model->selectRaw($selectFields,[$keywords])->distinct();
                        foreach ($joinConditions as $condition){
                            $model = $model->leftJoin($condition['table'],$table_name.'.'.$condition['parent_join_field'],'=',$condition['table'].'.'.$condition['join_field']);
                        }
                        $model = $model->whereRaw($where,$bindings)->groupBy(['cv_candidat.id_candidat'])->orderByRaw($orderBy,[$keywords]);
                    }
                } else {
                    $model = $model->whereRaw("MATCH ($searchFieldsStr) AGAINST (? IN BOOLEAN MODE)",[$keywords]);
                }
            }
        }
        return $model;
    }
}
