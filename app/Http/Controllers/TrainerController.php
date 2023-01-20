<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\Team;
use App\Models\Inscription;

class TrainerController extends Controller
{
    function store(Request $request)
    {
        $inscription  = Inscription::first();

        if(!$inscription->inscription_period){
            return response()->json([
                'message' => 'Por el momento el periodo de inscripción se encuentra finalizado'
            ], 423); 
        }

        $request->validate([
            'email' => 'required|email|unique:trainers',
            'name' => 'required',
            'lastname' => 'required',
            'date_birth' => 'required|date',
            'pokemons' => 'required|array'
        ]);
        
        $trainer = new Trainer;
        $trainer->email = $request->email;
        $trainer->name = $request->name;
        $trainer->lastname = $request->lastname;
        $trainer->date_birth = $request->date_birth;
        $trainer->save();

        foreach ($request->pokemons as  $pokemon) {
            $team = new Team;
            $team->pokemon = $pokemon['pokemon'];
            $team->url = $pokemon['url'];
            $team->trainer_id = $trainer->id;
            $team->save();
        }
        
        return ['success' => true];
    }


    public function index(Request $request)
    {
        extract($request->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

        $fields = ['id','name','lastname','email','date_birth'];

        $data = Trainer::with('team')->select($fields);

        if (isset($query) && $query) {
            $data = $byColumn == 1 ?
                $this->filterByColumn($data, $query) :
                $this->filter($data, $query, $fields);
        }

        $count = $data->count();

        $data->limit($limit)
            ->skip($limit * ($page - 1));

        if (isset($orderBy)) {
            $direction = $ascending == 1 ? 'ASC' : 'DESC';
            $data->orderBy($orderBy, $direction);
        }

        $results = $data->get()->toArray();

        return [
            'data' => $results,
            'count' => $count,
        ];
    }

    protected function filterByColumn($data, $queries)
    {
        return $data->where(function ($q) use ($queries) {
            foreach ($queries as $field => $query) {
                if (is_string($query)) {
                    $q->where($field, 'LIKE', "%{$query}%");
                } else {
                    $start = Carbon::createFromFormat('Y-m-d', $query['start'])->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $query['end'])->endOfDay();

                    $q->whereBetween($field, [$start, $end]);
                }
            }
        });
    }

    protected function filter($data, $query, $fields)
    {
        return $data->where(function ($q) use ($query, $fields) {
            foreach ($fields as $index => $field) {
                $method = $index ? 'orWhere' : 'where';
                $q->{$method}($field, 'LIKE', "%{$query}%");
            }
        });
    }
}
