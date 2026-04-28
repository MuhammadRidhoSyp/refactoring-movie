<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MovieService
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
        // Using find() because detail() used find(), though update() used findOrFail(). We'll use findOrFail for safety where appropriate.
        return Movie::find($id);
    }

    public function createMovie(array $data, $file = null)
    {
        if ($file) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;

            $file->move(public_path('images'), $fileName);
            $data['foto_sampul'] = $fileName;
        }

        return Movie::create($data);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $movie = Movie::findOrFail($id);

        if ($file) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = $randomName . '.' . $fileExtension;

            $file->move(public_path('images'), $fileName);

            if ($movie->foto_sampul && File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $data['foto_sampul'] = $fileName;
        }

        $movie->update($data);

        return $movie;
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->foto_sampul && File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        $movie->delete();
    }
}
