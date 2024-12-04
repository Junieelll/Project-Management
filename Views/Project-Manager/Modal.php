<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../styles/Project-Manager-Styles/home.css" />
  <link
    rel="stylesheet"
    href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />

</head>
<body>
 <!-- View Task -->
<div class="task-overlay-admin" id="taskModalOverlayAdmin"></div>
<div class="task-modal-admin" id="taskModalAdmin">
    <div class="task-modal-content-admin">
        <div class="close-btn-admin" id="closeTaskModalAdmin">
            <img src="../../images/close.png" alt="Close" onclick="">
        </div>  
        <div class="userimage"><img src="../../images/profile.png" alt="Close"></div>
        <div class="membername-admin">Member Name</div>
        <div class="devspeherename-admin">DevSphere: Developer Collaboration Website</div>

        <div class="duedate-admin">
            <label class="label-admin" for="taskDateAdmin"><img src="../../images/clock.png" alt="Clock Icon"></label>
            <p id="dateDisplayAdmin">Tuesday, September 15</p>
        </div>

        <div class="task-priority-admin">
            <span>Priority: </span><span class="task-priority-indicator-admin high">High</span>
        </div>

        <div class="note-admin">Notes</div>
        <p class="text-admin">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
        </p>
        <div class="edit-btn-admin">
            <img src="../../images/edit.png" alt="edit" onclick="">
        </div>
    </div>
</div>

<div class="task-overlay-add" id="taskModalOverlayAdd"></div>

<!-- Trigger button -->
<!-- <button id="openModal">Add Task</button>

<div class="modal" id="taskModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Task</h2>
            <button class="close-btn" id="closeModal">&times;</button>
        </div>
        <form id="addTaskForm">
            <div>
                <label for="taskTitle">Task Title:</label>
                <input type="text" id="taskTitle" name="task_title" required>
            </div>
            <div>
                <label for="note">Note:</label>
                <textarea id="note" name="note"></textarea>
            </div>
            <div>
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="due_date" required>
            </div>
            <div>
                <label for="assignedUserSearch">Assigned User:</label>
                <input type="text" id="assignedUserSearch" placeholder="Search for a user" required>
                <div class="search-result" style="display: none;"></div>
            </div>
            <input type="hidden" name="assigned_user">
            <div>
                <label for="priority">Priority:</label>
                <select id="priority" name="priority" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div>
                <label for="taskGroup">Task Group:</label>
                <input type="text" id="taskGroup" name="task_group" required>
            </div>
            <input type="hidden" name="project_id" value="<?php echo $_GET['project_id']; ?>">
            <div class="modal-footer">
                <button type="submit">Add Task</button>
            </div>
        </form>
    </div>
</div> -->


</body>
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modal = document.getElementById('taskModal');
    const assignedUserSearch = document.getElementById('assignedUserSearch');
    const addTaskForm = document.getElementById('addTaskForm');
    const searchResultDiv = document.querySelector('.search-result');
    let debounceTimeout;

    // Extract project_id from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get('project_id');

    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Event listener for input in the search bar with debounce
    assignedUserSearch.addEventListener('input', function() {
        const searchValue = assignedUserSearch.value.trim();

        if (debounceTimeout) {
            clearTimeout(debounceTimeout);
        }

        if (searchValue.length > 0 && projectId) {
            debounceTimeout = setTimeout(() => {
                fetch('../../controller/fetch_projectmembers.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: search=${encodeURIComponent(searchValue)}&project_id=${encodeURIComponent(projectId)}
                })
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        // Display search results
                        searchResultDiv.innerHTML = 
                            <ul>
                                ${data.map(user => 
                                    <li data-user="${user.user_id}">${user.email}</li>
                                ).join('')}
                            </ul>
                        ;
                        searchResultDiv.style.display = data.length > 0 ? 'block' : 'none';
                    } else {
                        // Handle error responses from PHP
                        searchResultDiv.innerHTML =<ul>
                                    <li>No users found</li>
                            </ul>;
                    }
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                    alert('An error occurred while fetching users.');
                    searchResultDiv.style.display = 'none';
                });
            }, 100); // 300ms debounce time to reduce the number of requests
        } else {
            searchResultDiv.style.display = 'none';
        }
    });

    // Event listener for clicking a search result
    searchResultDiv.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI') {
            const selectedUser = e.target.getAttribute('data-user');
            assignedUserSearch.value = e.target.textContent.trim();
            document.getElementsByName('assigned_user')[0].value = selectedUser;
            searchResultDiv.style.display = 'none';
        }
    });

    // Form submission with fetch
    addTaskForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addTaskForm);
        fetch('../../controller/add_task.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task added successfully!');
                modal.style.display = 'none';
                addTaskForm.reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error adding task:', error);
            alert('An error occurred while adding the task.');
        });
    });
}); -->

</script>
</html>