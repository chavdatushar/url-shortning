<?php

namespace App\Interfaces;

interface UrlRepositoryInterfaces{
    public function getAll();
    public function getDatatable();
    public function getById($id);
    public function delete($id);
    public function create(array $details);
    public function update($id, array $newDetails);

}