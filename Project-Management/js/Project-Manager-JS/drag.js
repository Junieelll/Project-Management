document.addEventListener('DOMContentLoaded', () => {
    // Fetch tasks from the server
    fetch('../../controller/fetch_task.php')
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
  
                // Now initialize drag-and-drop functionality
                initializeDragAndDrop();
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
  
    card.innerHTML = `
        <h3>${task.task_title}</h3>
        <section>Task Description: ${task.note || 'No description available'}</section>
        <div class="taskcard"><img src="${task.assigned_user_picture || '../../images/logo.png'}"></div>
        <p>${task.assigned_user_name || 'Unassigned'}</p>
        <span class="priority ${task.priority}">${task.priority}</span>
        <button class="delete1">
          <img src="../../images/home.png">
        </button>
        <button class="confirm-btn">Confirm Status Change</button>
        <input type="checkbox" class="task-select" />
    `;
  
    return card;
}
  
// Function to initialize drag-and-drop functionality
function initializeDragAndDrop() {
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
            }
        });
    });
  
    // Add event listener to confirm buttons
    document.querySelectorAll('.confirm-btn').forEach(button => {
        button.addEventListener('click', () => {
            const taskCard = button.closest('.task-card');
            const taskId = taskCard.dataset.taskId;
            const newStatus = taskCard.dataset.status;
  
            // Send an AJAX request to update the status in the database
            updateTaskStatus(taskId, newStatus);
        });
    });
  
    // Add event listener to select multiple tasks
    const confirmBulkButton = document.getElementById('confirm-bulk-status-change');
    confirmBulkButton.addEventListener('click', () => {
        const selectedTasks = [];
        document.querySelectorAll('.task-select:checked').forEach(checkbox => {
            const taskCard = checkbox.closest('.task-card');
            const taskId = taskCard.dataset.taskId;
            const newStatus = taskCard.dataset.status;
            selectedTasks.push({ taskId, newStatus });
        });
  
        if (selectedTasks.length > 0) {
            bulkUpdateTaskStatus(selectedTasks);
        } else {
            alert('No tasks selected');
        }
    });
}
  
// Function to update task status in the database
function updateTaskStatus(taskId, newStatus) {
    // Create data to send to the server
    const data = {
        tasks: [
            {
                taskId: taskId, 
                newStatus: newStatus // Send new status as the container ID (e.g., 'Backlog', 'InProgress')
            }
        ]
    };

    // Send an AJAX POST request to update the task status
    fetch('../../controller/update_task_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Task status updated successfully');
            // Reload the page after successful status update
            location.reload();
        } else {
            console.error('Failed to update task status');
        }
    })
    .catch(error => console.error('Error:', error));
}
  
// Function to update multiple tasks status in the database
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
            // Reload the page after successful status update
            location.reload();
        } else {
            console.error('Failed to update task statuses');
        }
    })
    .catch(error => console.error('Error updating task statuses:', error));
}
