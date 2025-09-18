<link rel="stylesheet" href="assets/css/fullcalendar.min.css">
<?php

include "assets/db/db_connect.php";
global $conn;

?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div id="todo-app" class="animate__animated p-6">
        <h2>My Todo List</h2>
        <input type="text" id="new-task" placeholder="Yeni görev...">
        <button onclick="addTodo()">Ekle</button>
        <ul id="todo-list">
            <!-- Görevler burada listelenecek -->
        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchTodos();
    });

    function fetchTodos() {
        $.ajax({
            url: 'pages/manager/todo/fetch_todos.php',
            method: 'GET',
            success: function(data) {
                const todos = JSON.parse(data);
                let todoList = '';
                todos.forEach(todo => {
                    todoList += `<li>
                    <input type="checkbox" ${todo.completed ? 'checked' : ''} onchange="toggleTodo(${todo.id}, this.checked)">
                    ${todo.task}
                    <button onclick="deleteTodo(${todo.id})">Sil</button>
                </li>`;
                });
                document.getElementById('todo-list').innerHTML = todoList;
            }
        });
    }

    function addTodo() {
        const task = document.getElementById('new-task').value;
        if (task) {
            $.ajax({
                url: 'pages/manager/todo/add_todo.php',
                method: 'POST',
                data: { task },
                success: function(response) {
                    document.getElementById('new-task').value = '';
                    fetchTodos();
                }
            });
        }
    }

    function toggleTodo(id, completed) {
        $.ajax({
            url: 'pages/manager/todo/update_todo.php',
            method: 'POST',
            data: { id, completed: completed ? 1 : 0 },
            success: function(response) {
                fetchTodos();
            }
        });
    }

    function deleteTodo(id) {
        $.ajax({
            url: 'pages/manager/todo/delete_todo.php',
            method: 'POST',
            data: { id },
            success: function(response) {
                fetchTodos();
            }
        });
    }

</script>