document.addEventListener('DOMContentLoaded', () => {
    
    // Object to track status changes
    const statusChanges = {};

    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get("project_id");

    // Fetch tasks from the server
    fetch(`../../controller/fetch_task.php?project_id=${projectId}`)
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
//closeModalButton.addEventListener('click', closeModal);

// Optional: Close modal when overlay is clicked
//modalOverlay.addEventListener('click', closeModal);

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
