document.addEventListener('DOMContentLoaded', () => {

    // Object to track status changes
    const statusChanges = {};

    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get("project_id");

    // Fetch tasks from the server
    fetch(`Project-Management/controller/fetch_task.php?project_id=${projectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tasks = data.tasks;

                // Clear existing tasks in each container
                document.getElementById('Backlog').innerHTML = '<h5>BACKLOG</h5>';
                document.getElementById('InProgress').innerHTML = '<h5>IN PROGRESS</h5>';
                document.getElementById('Testing').innerHTML = '<h5>TESTING</h5>';
                document.getElementById('Finished').innerHTML = '<h5>FINISHED</h5>';

                // Loop through tasks and append them to the corresponding containers
                tasks.forEach(task => {
                    const taskElement = createTaskCard(task);

                    switch (task.status) {
                        case 'Backlog':
                            document.getElementById('Backlog').appendChild(taskElement);
                            break;
                        case 'InProgress':
                            document.getElementById('InProgress').appendChild(taskElement);
                            break;
                        case 'Testing':
                            document.getElementById('Testing').appendChild(taskElement);
                            break;
                        case 'Finished':
                            document.getElementById('Finished').appendChild(taskElement);
                            break;
                    }
                });

                // Initialize drag-and-drop functionality
                initializeDragAndDrop(statusChanges);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching tasks:', error));
});
// Function to create a task card element
function createTaskCard(task) {
    const card = document.createElement('div');
    card.className = `task-card ${task.status}`;
    card.draggable = true;
    card.dataset.taskId = task.task_id; // Store task ID on the card
    card.dataset.status = task.status; // Store status on the card

    // Attach click event to open the modal
    card.onclick = function () {
        showModal(task); // Pass the task object to the modal
    };

    card.innerHTML = `
        <h3>${task.task_title}</h3>
        <section>Task Description: ${task.note || 'No description available'}</section>
        <div class="taskcard"><img src="${task.assigned_user_picture || '../../images/trash.png'}" alt="User Image"></div>
        <p>${task.assigned_user_name || 'Unassigned'}</p>
        <span class="priority ${task.priority}">${task.priority}</span>
        <button class="delete1">
            <img src="../../images/trash.png" alt="Delete Task">
        </button>
    `;
    const deleteButton = card.querySelector('.delete1');
    deleteButton.onclick = function (e) {
        e.stopPropagation(); // Prevent triggering the card's onclick event
        deleteTask(task.task_id, card);
    };

    return card;
}
function deleteTask(taskId, card) {
    const confirmation = confirm("Are you sure you want to delete this task?");
    if (confirmation) {
        // Send AJAX request to delete the task
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../controller/delete_task.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Remove the task card from the DOM
                card.remove();
            } else {
                alert('Failed to delete the task.');
            }
        };
        xhr.send(JSON.stringify({ task_id: taskId }));
    }
}
// Modal Elements
const modalOverlay = document.getElementById('taskModalOverlayAdmin');
const modal = document.getElementById('taskModalAdmin');
const closeModalButton = document.getElementById('closeTaskModalAdmin');

// Function to show modal and populate it with task data
function showModal(task) {
    console.log(`Opening modal for Task ID: ${task.task_id}`);

    // Populate modal elements with task data
    document.querySelector('.membername-admin').textContent = task.assigned_user_name || 'Unassigned';

    const userImage = document.querySelector('.userimage img');
    userImage.src = task.assigned_user_picture || 'IMG/user.png'; // Use default image if not provided

    document.querySelector('.devspeherename-admin').textContent = task.task_title || 'No Title';

    document.getElementById('dateDisplayAdmin').textContent = task.due_date || 'No Due Date';

    const priorityIndicator = document.querySelector('.task-priority-indicator-admin');
    priorityIndicator.textContent = task.priority || 'None';
    priorityIndicator.className = `task-priority-indicator-admin ${task.priority?.toLowerCase() || ''}`;

    // Populate notes
    document.querySelector('.text-admin').textContent = task.note || 'No additional notes provided.';

    // Show modal
    modal.style.display = 'block';
    modalOverlay.style.display = 'block';
}

// Function to close modal
function closeModal() {
    modal.style.display = 'none';
    modalOverlay.style.display = 'none';
}

// Event listener for close button
closeModalButton.addEventListener('click', closeModal);

// Optional: Close modal when overlay is clicked
modalOverlay.addEventListener('click', closeModal);

// Function to initialize drag-and-drop functionality
function initializeDragAndDrop(statusChanges) {
    const taskCards = document.querySelectorAll('.task-card');
    const dropContainers = document.querySelectorAll('.cards-container > div'); // All task container divs

    taskCards.forEach(card => {
        card.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('task_id', card.dataset.taskId); // Store task ID or other relevant data
        });
    });

    dropContainers.forEach(container => {
        container.addEventListener('dragover', (e) => {
            e.preventDefault(); // Necessary to allow the drop
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            const taskId = e.dataTransfer.getData('task_id'); // Get the task ID
            const taskCard = document.querySelector(`.task-card[data-task-id='${taskId}']`);
            if (taskCard) {
                container.appendChild(taskCard); // Move the task card to the new container

                // Update the task's status locally
                const newStatus = container.id; // The new status is the ID of the container
                taskCard.dataset.status = newStatus; // Update status on the card

                // Track status change
                if (!statusChanges[taskId]) {
                    statusChanges[taskId] = {
                        oldStatus: taskCard.dataset.status,
                        newStatus: newStatus
                    };
                } else {
                    statusChanges[taskId].newStatus = newStatus; // Update new status if it's already tracked
                }

                taskCard.classList.remove('Backlog', 'InProgress', 'Testing', 'Finished'); // Remove all status classes
                taskCard.classList.add(newStatus); // Add the new status class
            }
        });
    });

    // Add event listener to confirm bulk status changes
    const confirmBulkButton = document.getElementById('confirm-bulk-status-change');
    confirmBulkButton.addEventListener('click', () => {
        const tasksToUpdate = [];
        for (const taskId in statusChanges) {
            if (statusChanges.hasOwnProperty(taskId)) {
                const task = statusChanges[taskId];
                tasksToUpdate.push({
                    taskId: taskId,
                    newStatus: task.newStatus
                });
            }
        }

        if (tasksToUpdate.length > 0) {
            bulkUpdateTaskStatus(tasksToUpdate);
        } else {
            alert('No status changes to confirm');
        }
    });
}

// Function to update multiple tasks' status in the database
function bulkUpdateTaskStatus(tasks) {
    fetch('../../controller/bulk_update_task_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ tasks })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Task statuses updated successfully');
            // Optionally reload the page after successful status update
            location.reload();
        } else {
            console.error('Failed to update task statuses');
        }
    })
    .catch(error => console.error('Error updating task statuses:', error));
}
// Show the modal and overlay
// Function to open the modal

function openaddtaskModal() {
    document.querySelector('.modal-add').classList.add('show');
    document.querySelector('.modal-overlay').classList.add('show');
}

// Function to close the modal
function closeaddtaskModal() {
    document.querySelector('.modal-add').classList.remove('show');
    document.querySelector('.modal-overlay').classList.remove('show');
}

// Close the modal when clicking outside the modal content
document.querySelector('.modal-overlay').addEventListener('click', function (event) {
    // Only close the modal if the clicked area is the overlay (not the modal content)
    if (event.target === this) {
        closeaddtaskModal();
    }
});

const searchInput = document.getElementById('memberSearch');
const searchResult = document.querySelector('.search-result');
const membersDiv = document.getElementById('projMember');
const urlParams = new URLSearchParams(window.location.search);
const projectId = urlParams.get("project_id");

searchInput.addEventListener('input', function () {
    const searchValue = searchInput.value.trim();

    // Hide results if the input is too short
    if (searchValue.length < 3) {
        searchResult.style.display = 'none';
        return;
    }

    // Send the search query via POST method
    fetch('/Project-Management/controller/fetch_projectmembers.php', {
        method: 'POST',  // Use POST to send the data in the body
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            project_id: projectId,
            search: searchValue,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const members = data.members;
            searchResult.innerHTML = ''; 
            searchResult.style.display = 'flex';

            if (members.length > 0) {
                members.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.classList.add('user');
                    userDiv.dataset.userId = user.user_id;

                    userDiv.innerHTML = `
                        <p>${user.email}</p>
                        <button class="add-btn" type="button">Add</button>
                    `;

                    // Event listener to add the user to the selected members
                    userDiv.querySelector('.add-btn').addEventListener('click', function () {
                        if (!membersDiv.querySelector(`[data-user-id="${user.user_id}"]`)) {
                            const selectedDiv = document.createElement('div');
                            selectedDiv.classList.add('user');
                            selectedDiv.dataset.userId = user.user_id;
                            selectedDiv.innerHTML = `
                                <p>${user.email}</p>
                                <button class="remove-btn"><i class="fa-light fa-circle-xmark"></i></button>
                            `;

                            // Event listener to remove the user from the selected members
                            selectedDiv.querySelector('.remove-btn').addEventListener('click', function () {
                                selectedDiv.remove(); 
                            });

                            membersDiv.appendChild(selectedDiv);
                        }

                        // Remove the user from the search results after adding
                        userDiv.remove();
                    });

                    searchResult.appendChild(userDiv);
                });
            } else {
                searchResult.innerHTML = '<p>No users found.</p>';
            }
        } else {
            console.error('Failed to fetch project members:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

document.getElementById('createTaskBtnAdd').addEventListener('click', function () {
    // Get values from the form
    const taskTitle = document.getElementById('task-title-add').value.trim();
    const note = document.getElementById('note-add').value.trim();
    const dueDate = document.getElementById('due-date-add').value.trim();
    const priority = document.getElementById('priority-add').value;
    const group = document.getElementById('group-add').value;
    const assignedUser = document.getElementById('memberSearch').value;
    
    //Array.from(document.getElementById('projMember').children).map(user => user.dataset.userId);
    
    // Check if required fields are filled
    if (!taskTitle || !priority || !group) {
        alert("Please fill in all required fields.");
        return;
    }

    // Prepare the data to be sent
    const taskData = {
        task_title: taskTitle,
        note: note,
        due_date: dueDate,
        priority: priority,
        task_group: group,
        status: 'Backlog',  // Set status to "New" by default, or modify based on your needs
        assigned_user: assignedUser.join(','),  // Join user IDs for multiple users
        project_id: projectId, // Replace with dynamic project ID
        file: null,  // Add file if needed
    };

    // Send the data via POST to the PHP backend
    fetch('/Project-Management/controller/add_task.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(taskData),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Task added successfully!');
            closeaddtaskModal();  // Close the modal after successful submission
        } else {
            alert('Failed to add task: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error occurred while adding the task.');
    });
});