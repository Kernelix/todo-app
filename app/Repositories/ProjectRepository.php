<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Doctrine\DBAL\DriverManager;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $connection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'driver' => 'pdo_pgsql',
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function all()
    {
        $query = "SELECT p.*,
          (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND user_id = ? AND deleted_at IS NULL) as tasks_count
          FROM projects p
          WHERE p.deleted_at IS NULL";

        return $this->connection->fetchAllAssociative($query, [auth()->id()]);
    }

    public function find($id)
    {
        $query = "SELECT * FROM projects WHERE id = ? AND deleted_at IS NULL AND user_id = ?";
        return $this->connection->fetchAssociative($query, [$id, auth()->id()]);
    }

    public function create(array $data)
    {
        $data['user_id'] = auth()->id();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $this->connection->insert('projects', $data);
        return $this->connection->lastInsertId();
    }

    public function update($id, array $data)
    {
        $data['updated_at'] = now();
        $this->connection->update('projects', $data, ['id' => $id]);
        return $id;
    }

    public function delete($id)
    {
        $this->connection->update(
            'projects',
            ['deleted_at' => now()],
            ['id' => $id, 'user_id' => auth()->id()]
        );
    }

    public function findOrFail(int $id)
    {
        return Project::findOrFail($id);
    }
}
