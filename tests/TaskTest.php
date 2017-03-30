<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=practice_db_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class TaskTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Category::deleteAll();
          Task::deleteAll();
        }

        // Test your getters and setters.
        function test_getId()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();

            $description = "Wash the dog";
            $category_id = $test_category->getId();
            $test_task = new Task($description, $category_id);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_getDescription()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();
            // no need to pass in id because it is null by default.
            $description = "Watch the new Thor movie.";
            $test_task = new Task($description, $category_id);

            //Act
            $result = $test_task->getDescription();

            //Assert
            // id is null here, but that is not what we are testing. We are only interested in description.
            $this->assertEquals($description, $result);
        }

        function test_setDescription()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Watch the new Thor movie.";
            $test_task = new Task($description, $category_id);
            $new_description = "Watch the new Star Wars movie.";

            //Act
            $test_task->setDescription($new_description);
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($new_description, $result);
        }

        function test_getCategoryId()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Watch the new Thor movie.";
            $test_task = new Task($description, $category_id);

            //Act
            $result = $test_task->getCategoryId();

            //Assert
            $this->assertEquals($category_id, $result);
        }

        function test_setCategoryId()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $name = "Books to Read";
            $test_category2 = new Category($name);
            $test_category2->save();
            $category_id2 = $test_category2->getId();

            $description = "Watch the new Thor movie.";
            $test_task = new Task($description, $category_id);

            //Act
            $test_task->setCategoryId($category_id2);
            $result = $test_task->getCategoryId();

            //Assert
            $this->assertEquals($category_id2, $result);
        }

        function test_save()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Eat breakfast.";
            $test_task = new Task($description, $category_id);
            $test_task->save(); // id gets created by database and written in during save method.

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals($test_task, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            // create more than one task to make sure getAll returns them all.
            $description = "Eat breakfast.";
            $description2 = "Eat lunch.";
            $test_task = new Task($description, $category_id);
            $test_task->save();
            $test_task2 = new Task($description2, $category_id);
            $test_task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Movies To Watch";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            // create more than one task
            $description = "Eat breakfast.";
            $description2 = "Eat lunch.";
            $test_task = new Task($description, $category_id);
            $test_task->save();
            $test_task2 = new Task($description2, $category_id);
            $test_task2->save();

            //Act
            Task::deleteAll(); 
            $result = Task::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Home stuff";
            $test_category = new Category($name);
            $test_category->save();
            $category_id = $test_category->getId();

            $description = "Eat breakfast.";
            $description2 = "Eat lunch.";
            $test_task = new Task($description, $category_id);
            $test_task->save();
            $test_task2 = new Task($description2, $category_id);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }
    }
?>
