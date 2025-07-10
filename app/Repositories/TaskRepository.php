<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskRepositoryInterface
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

    /**
     * @throws Exception
     */
    public function all()
    {
        $query = "SELECT t.*, p.name as project_name
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.deleted_at IS NULL
                  AND t.user_id = ?";

        return $this->connection->fetchAllAssociative($query, [Auth::id()]);
    }

    public function find($id)
    {
        $query = "SELECT t.*, p.name as project_name
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.id = ?
                  AND t.deleted_at IS NULL
                  AND t.user_id = ?";

        return $this->connection->fetchAssociative($query, [$id, Auth::id()]);
    }

    public function create(array $data)
    {
        $validStatuses = ['pending', 'in_progress', 'testing', 'review', 'completed'];

        if (!in_array($data['status'], $validStatuses)) {
            throw new \InvalidArgumentException('Invalid task status');
        }

        $data['user_id'] = Auth::id();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $this->connection->insert('tasks', $data);
        return $this->connection->lastInsertId();
    }

    public function update($id, array $data)
    {
        if (isset($data['status'])) {
            $validStatuses = ['pending', 'in_progress', 'testing', 'review', 'completed'];
            if (!in_array($data['status'], $validStatuses)) {
                throw new \InvalidArgumentException('Invalid task status');
            }
        }

        $data['updated_at'] = now();
        $this->connection->update(
            'tasks',
            $data,
            ['id' => $id, 'user_id' => Auth::id()]
        );

        return $id;
    }

    public function delete($id)
    {
        $this->connection->update(
            'tasks',
            ['deleted_at' => now()],
            ['id' => $id, 'user_id' => Auth::id()]
        );
    }

    public function filter($status)
    {
        $validStatuses = ['pending', 'in_progress', 'testing', 'review', 'completed'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid task status');
        }

        $query = "SELECT t.*, p.name as project_name
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.status = ?
                  AND t.deleted_at IS NULL
                  AND t.user_id = ?";

        return $this->connection->fetchAllAssociative($query, [$status, Auth::id()]);
    }

    public function getByProject($projectId)
    {
        $query = "SELECT t.*, p.name as project_name
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.project_id = ?
                  AND t.deleted_at IS NULL
                  AND t.user_id = ?";

        return $this->connection->fetchAllAssociative($query, [$projectId, Auth::id()]);
    }

    public function changeStatus($id, $status)
    {
        $validStatuses = ['pending', 'in_progress', 'testing', 'review', 'completed'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid task status');
        }

        $this->connection->update(
            'tasks',
            [
                'status' => $status,
                'updated_at' => now()
            ],
            ['id' => $id, 'user_id' => Auth::id()]
        );
    }

    public function findOrFail(int $id)
    {
        return Task::findOrFail($id);
    }
}
