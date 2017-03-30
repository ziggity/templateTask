<?php
class Task
{
    private $description;
    private $category_id;
    private $id;

    function __construct($description, $assigned_category_id, $id = null)
    {
        $this->description = $description;
        $this->category_id = $assigned_category_id;
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getCategoryId()
    {
        return $this->category_id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function setCategoryId($new_category_id)
    {
        $this->category_id = (int) $new_category_id;
    }

    function save()
    {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id) VALUES ('{$this->getDescription()}', {$this->getCategoryId()})");
        if ($executed) {
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();
        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $id = $task['id'];
            $category_id = $task['category_id'];
            $new_task = new Task($description, $category_id, $id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
      $executed = $GLOBALS['DB']->exec("DELETE FROM tasks;");
        if ($executed) {
            return true;
        } else {
            return false;
        }
    }

    static function find($search_id)
    {
        $found_task = null;
        $returned_tasks = $GLOBALS['DB']->prepare("SELECT * FROM tasks WHERE id = :id");
        $returned_tasks->bindParam(':id', $search_id, PDO::PARAM_STR);
        $returned_tasks->execute();
        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $category_id = $task['category_id'];
            $id = $task['id'];
            if ($id == $search_id) {
              $found_task = new Task($description, $category_id, $id);
            }
        }
        return $found_task;
    }
}
?>
