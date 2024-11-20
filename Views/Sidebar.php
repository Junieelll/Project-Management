<style>
    .sidebar {
        padding: 20px;
        left: -270px;
        height: 100%;
        width: 250px;
        position: fixed;
        top: 60px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #3d437d;
        filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.3));
        transition: left 0.5s ease;
        z-index: 9;
        display: flex;
        flex-direction: column;
    }

    .sidebar.open {
        left: 0;
    }

    .sidebar .title {
        color: white;
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 20px 0;
        justify-content: space-between;
    }

    .title-icon {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .title img {
        width: 40px;
        height: auto;
    }

    #addProject {
        all: unset;
        font-size: 25px;
        cursor: pointer;
    }

    .projects {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 30px;
        height: 255px;
        overflow-y: auto;
    }

    ::-webkit-scrollbar {
    width: 5px;
    }

    ::-webkit-scrollbar-track {
    background: #3D437D; 
    }
    
    ::-webkit-scrollbar-thumb {
    background: #54C6FF; 
    border-radius: 12px;
    }

    ::-webkit-scrollbar-thumb:hover {
    background: #555; 
    }

    .projects img {
        width: 20px;
    }

    .projects a {
        padding: 20px;
        color: #fff;
        background-color: #171D35;
        border-radius: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        gap: 10px;
        transition: all ease 0.3s;
    }

    .projects a:hover {
        background-color: #54C6FF;
    }

    .line {
        margin: 10px 0;
    }

    .others {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
    }

    .others a {
        color: #fff;
    }

    .others a:hover {
        color: #54C6FF;
        transition: all ease 0.3s;
    }

    .modal {
    display: flex;
    position: fixed;
    z-index: 10;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    opacity: 0; 
    visibility: hidden; 
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal.show {
    opacity: 1; 
    visibility: visible; 
}

.modal-content {
    background-color: #3D437D;
    color: #fff;
    margin: 5% auto;
    padding: 25px;
    width: 80%;
    max-width: 800px;
    height: max-content;
    border-radius: 25px;
    box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px;
    transform: translateY(-50px); 
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal.show .modal-content {
    transform: translateY(0);
    opacity: 1;
}


.modal-header h3{
    text-align: center;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
}

.modal-body{
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input{
    display: flex;
    gap: 20px;
    align-items: center;
    width: 100%;
}

.input label{
    font-size: 14px;
    padding-left: 5px;
    text-wrap: nowrap;
}

.input input, #due-date{
    outline: none;
    padding: 10px;
    border-radius: 25px;
    font-size: 12px;
    width: 100%;
    border: none;
}

.half-width{
    display: flex;
    gap: 20px;
    padding: 10px;
}

.description{
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.description label{
    font-size: 13px;
}


.half-width textarea{
    width: 100%;
    resize: none;
    height: 300px;
    border-radius: 25px;
    background-color: rgba(255, 255, 255, 0.5);
    outline: none;
    padding: 20px;
    border: none;
}

.half-width textarea::placeholder{
    color: #fff;
}

.add-members{
    width: 100%;
    background-color: #171D35;
    border-radius: 25px;
    max-width: 300px;
    padding: 15px;
}

.modal-footer{
    display: flex;
    padding: 7px 10px;
    align-items: center;
    justify-content: end;
}

.action-btn{
    display: flex;
    gap: 10px;
}

.action-btn button{
    font-size: 13px;
    border: 1px solid #f1f1f1;
    padding: 10px 20px;
    border-radius: 10px;
    width: 100%;
    cursor: pointer;
    text-wrap: nowrap;
}

.create-btn{
    border-color: #3BB800 !important;
    background-color: #3BB800;
    color: #fff;
}

#addProjectModal .search-input .search1{
    color: #000;
}

.add-members h5{
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 500;
}

.user{
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.add-members .members{
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 15px 10px;
}

.user p{
    font-size: 12px;
    text-overflow: ellipsis;
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
}

.remove-btn{
    all: unset;
    cursor: pointer;
}

.logout-btn {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: none;
    margin: auto;
    background-color: #f1f1f1;
    color: #e74c3c;
    text-align: center;
    font-size: 12px;
}

.search-result{
    position: absolute;
    background: rgba(255, 255, 255, 0.23);
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(18.2px);
    -webkit-backdrop-filter: blur(18.2px);
    border: 1px solid rgba(0, 0, 0, 0.1);
    width: calc(100% - 20px);
    color: #fff;
    padding: 15px;
    flex-direction: column;
    gap: 7px;
    margin-top: 5px;
    width: 100%;
    display: none;
}

.add-btn{
    border: none;
    border-radius: 50px;
    padding: 7px 10px;
    font-size: 11px;
    cursor: pointer;
    background-color: #54C6FF;
    color: #fff;
}

.toast {
    visibility: hidden;
    min-width: 250px;
    max-height: fit-content;
    margin-left: -125px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 10px;
    padding: 15px 30px;
    position: fixed;
    z-index: 99;
    left: 50%;
    top: 70px;
    font-size: 13px;
    white-space: nowrap;
}

.toast.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 4.5s;
    animation: fadein 0.5s, fadeout 0.5s 4.5s;
}

.toast-error {
    background-color: #e74c3c; 
}

.toast-success {
    background-color: #2ecc71; 
}

.toast-info {
    background-color: #0d6efd; 
}

.toast i{
    margin-right: 5px;
}

@-webkit-keyframes fadein {
    from {bottom: 0; opacity: 0;} 
    to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {bottom: 30px; opacity: 1;} 
    to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 0; opacity: 0;}
}

</style>

<aside class="sidebar">
    <hr class="line" />

    <div class="title">
        <div class="title-icon">
        <i class="fa-solid fa-file-code"></i>
            <h5>PROJECTS</h5>
        </div>
        <button id="addProject" class="add-project"><i class="fa-regular fa-plus"></i></button>
    </div>

    <div class="projects">

    </div>

    <hr class="line" />

    <div class="others">
        <a href="">Courses</a>
        <a href="">Assessments</a>
        <a href="">Learning Path</a>
    </div>

    <a class="logout-btn" href="/PROJECT-MANAGEMENT/auth/logout.php"><i class="fa-light fa-right-from-bracket"></i> Logout</a>
</aside>

<div id="addProjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Project</h3>
        </div>
        <form id="addProjectForm">
            <div class="modal-body">
                <div class="input">
                    <label for="project-name">Project Name</label>
                    <input type="text" placeholder="Project Name" id="name" autocomplete="off"> 
                </div>
                <div class="half-width">
                    <div class="add-members">
                        <h5>Add Member</h5>
                        <div class="search-input">
                            <i class="fa-solid fa-magnifying-glass search1"></i>
                            <input type="text" name="" id="memberSearch" placeholder="Search a user" autocomplete="off">
                            <div class="search-result"></div>
                        </div>
                        <div class="members" id="projMember">
                            
                        </div>
                    </div>
                    <div class="description">
                        <label for="description">Description</label>
                        <textarea name="" id="description" placeholder="Enter Description Here..."></textarea>
                        <label for="due-date">Due Date</label>
                        <input type="date" name="" id="due-date">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="action-btn">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="create-btn">Create Project</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
     document.addEventListener('DOMContentLoaded', function() {
    const addProjectModal = document.getElementById('addProjectModal');
    const addProjectBtn = document.querySelectorAll('.add-project');
    const cancelBtn = document.querySelector('.cancel-btn');

    addProjectBtn.forEach(function(button) {
        button.addEventListener('click', function() {
            addProjectModal.classList.add('show');
        });
    });

    cancelBtn.addEventListener('click', function() {
        addProjectModal.classList.remove('show');
    });

    document.querySelectorAll('.projects a').forEach(link => {
        const maxLength = 20;
        const textNode = Array.from(link.childNodes).find(node => node.nodeType === Node.TEXT_NODE);

        if (textNode && textNode.textContent.length > maxLength) {
            textNode.textContent = textNode.textContent.slice(0, maxLength) + '...';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#due-date", {
        dateFormat: "Y-m-d", 
    });
});

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('open');
    }

    const sidebarBtn = document.getElementById('sidebarBtn');

    sidebarBtn.addEventListener('click', toggleSidebar);

    document.querySelector('#addProjectForm').addEventListener('submit', function (e) {
    e.preventDefault(); 

    const projectName = document.querySelector('#name').value.trim();
    const description = document.querySelector('#description').value.trim();
    const dueDate = document.querySelector('#due-date').value;
    const members = Array.from(document.querySelectorAll('.members .user')).map(user => user.dataset.userId);

    if (!projectName || !description || !dueDate) {
        showToast('<i class="fa-regular fa-triangle-exclamation"></i> Please fill out all fields.', 'error');
        return;
    }

    fetch('/PROJECT-MANAGEMENT/controller/add_project.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            project_name: projectName,
            description: description,
            due_date: dueDate,
            status: status,
            members: members,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('<i class="fa-light fa-circle-check"></i> Project Created Successfully!', 'success');
            fetchProjects();
            location.reload();
        } else {
            showToast('<i class="fa-regular fa-triangle-exclamation"></i> Failed to create project.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});



    const searchInput = document.getElementById('memberSearch');
const searchResult = document.querySelector('.search-result');
const membersDiv = document.getElementById('projMember');

searchInput.addEventListener('input', function () {
    const searchValue = searchInput.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(searchValue)) {
        searchResult.style.display = 'none';
        return;
    }

    fetch('/PROJECT-MANAGEMENT/controller/search_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({ search: searchValue }),
    })
        .then(response => response.json())
        .then(users => {
            searchResult.innerHTML = '';
            searchResult.style.display = 'flex';

            if (users.length > 0) {
                users.forEach(user => {
                    const userDiv = document.createElement('div');
                    userDiv.classList.add('user');
                    userDiv.dataset.userId = user.user_id;

                    userDiv.innerHTML = `
                        <p>${user.email}</p>
                        <button class="add-btn" type="button">Add</button>
                    `;

                    userDiv.querySelector('.add-btn').addEventListener('click', function () {
                        if (!membersDiv.querySelector(`[data-user-id="${user.user_id}"]`)) {
                            const selectedDiv = document.createElement('div');
                            selectedDiv.classList.add('user');
                            selectedDiv.dataset.userId = user.user_id;
                            selectedDiv.innerHTML = `
                                <p>${user.email}</p>
                                <button class="remove-btn"><i class="fa-light fa-circle-xmark"></i></button>
                            `;

                            selectedDiv.querySelector('.remove-btn').addEventListener('click', function () {
                                selectedDiv.remove();
                            });

                            membersDiv.appendChild(selectedDiv);
                        }

                        userDiv.remove();
                    });

                    searchResult.appendChild(userDiv);
                });
            } else {
                searchResult.innerHTML = '<p>No users found.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});


function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.innerHTML = message;
    
    toast.className = 'toast show';
    if (type === 'error') {
        toast.classList.add('toast-error');
    } else if (type === 'success') {
        toast.classList.add('toast-success');
    } else {
        toast.classList.add('toast-info');
    }

    setTimeout(() => {
        toast.className = toast.className.replace('show', '');
    }, 3000);
}

function fetchProjects() {
    const projectsContainer = document.querySelector('.projects');

    fetch('/PROJECT-MANAGEMENT/controller/fetch_projects.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const projects = data.projects;

            projects.forEach(project => {
                const projectLink = document.createElement('a');
                projectLink.href = "#"; 
                projectLink.innerHTML = `<i class="fa-regular fa-folder-open"></i> ${project.project_name}`;
                projectLink.dataset.projectId = project.project_id; 

                projectLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    const projectId = projectLink.dataset.projectId;
                    checkUserRole(projectId); 
                });

                projectsContainer.appendChild(projectLink);
            });
        } else {
            console.error('Failed to fetch projects:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function checkUserRole(projectId) {
    fetch(`/PROJECT-MANAGEMENT/controller/check_user_role.php?project_id=${projectId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // kapag PM
                if (data.is_manager) {
                    window.location.href = `/PROJECT-MANAGEMENT/Views/Project-Manager/Home.php?project_id=${projectId}`;
                } else {
                    // kapag member
                    window.location.href = `/PROJECT-MANAGEMENT/Views/Member/Home.php?project_id=${projectId}`;
                }
            } else {
                console.error('Failed to check user role:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
    

fetchProjects();

 });

</script>