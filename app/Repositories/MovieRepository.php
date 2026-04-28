<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllMovies($search = null)
    {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        return $query->paginate(6)->withQueryString();
    }

    public function getMoviesForData()
    {
        return Movie::latest()->paginate(10);
    }

    public function getMovieById($id)
    {
        return Movie::find($id);
    }

    public function getMovieByIdOrFail($id)
    {
        return Movie::findOrFail($id);
    }

    public function createMovie(array $data)
    {
        return Movie::create($data);
    }

    public function updateMovie($id, array $data)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return true;
    }
}
