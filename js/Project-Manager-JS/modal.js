const modalOverlay = document.getElementById('taskModalOverlayAdmin');
const modal = document.getElementById('taskModalAdmin');
const closeModalButton = document.getElementById('closeTaskModalAdmin');

// Function to show modal
function showModal(taskId) {
    console.log(`Opening modal for Task ID: ${taskId}`);
    document.querySelector('.membername-admin').textContent = `${task.assigned_user_name}`;

    const userImage = document.querySelector('.userimage img');
    userImage.src = `${task.assigned_user_picture}` || 'IMG/user.png'; // Default image if none provided

    document.querySelector('.devspeherename-admin').textContent = `${task.task_title}`;

    document.getElementById('dateDisplayAdmin').textContent = `${task.due_date}`;

    const priorityIndicator = document.querySelector('.task-priority-indicator-admin');
    priorityIndicator.textContent = `${task.priority}`;
    priorityIndicator.className = `task-priority-indicator-admin ${task.priority}`;

    // Populate notes
    document.querySelector('.text-admin').textContent = `${task.note}` || 'No additional notes provided.';

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