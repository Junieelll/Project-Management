document.addEventListener('DOMContentLoaded', function () {
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

    openModalBtn.addEventListener('click', function () {
        modal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Event listener for input in the search bar with debounce
    assignedUserSearch.addEventListener('input', function () {
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
                    body: `search=${encodeURIComponent(searchValue)}&project_id=${encodeURIComponent(projectId)}`,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data)) {
                            // Display search results
                            searchResultDiv.innerHTML = `
                                <ul>
                                    ${data.map(user => `<li data-user="${user.user_id}">${user.email}</li>`).join('')}
                                </ul>
                            `;
                            searchResultDiv.style.display = data.length > 0 ? 'block' : 'none';
                        } else {
                            // Handle error responses from PHP
                            searchResultDiv.innerHTML = `
                                <ul>
                                    <li>No users found</li>
                                </ul>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching users:', error);
                        alert('An error occurred while fetching users.');
                        searchResultDiv.style.display = 'none';
                    });
            }, 300); // 300ms debounce time to reduce the number of requests
        } else {
            searchResultDiv.style.display = 'none';
        }
    });

    // Event listener for clicking a search result
    searchResultDiv.addEventListener('click', function (e) {
        if (e.target.tagName === 'LI') {
            const selectedUser = e.target.getAttribute('data-user');
            assignedUserSearch.value = e.target.textContent.trim();
            document.getElementsByName('assigned_user')[0].value = selectedUser;
            searchResultDiv.style.display = 'none';
        }
    });

    // Form submission with fetch
    addTaskForm.addEventListener('submit', function (event) {
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
});
