<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMovies($search = null)
    {
        return $this->movieRepository->getAllMovies($search);
    }

    public function getMoviesForData()
    {
        return $this->movieRepository->getMoviesForData();
    }

    public function getMovieById($id)
    {
        return $this->movieRepository->getMovieById($id);
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

        return $this->movieRepository->createMovie($data);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $movie = $this->movieRepository->getMovieByIdOrFail($id);

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

        return $this->movieRepository->updateMovie($id, $data);
    }

    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->getMovieByIdOrFail($id);

        if ($movie->foto_sampul && File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        $this->movieRepository->deleteMovie($id);
    }
}
