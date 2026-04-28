<?php

namespace App\Interfaces;

interface MovieRepositoryInterface
{
    public function getAllMovies($search = null);
    public function getMoviesForData();
    public function getMovieById($id);
    public function getMovieByIdOrFail($id);
    public function createMovie(array $data);
    public function updateMovie($id, array $data);
    public function deleteMovie($id);
}
