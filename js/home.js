document.addEventListener('DOMContentLoaded', function () {
    const projectsList = document.querySelector('.projects-list');

    projectsList.addEventListener('click', function (event) {
        // Identify the clicked delete button
        const clickedDeleteButton = event.target.closest('#deleteProject');
        const clickedOptionButton = event.target.closest('.option-btn');
        const clickedOptionContainer = event.target.closest('.option-container');

        if (clickedDeleteButton) {
            // Find the parent project card and its ID
            const projectCard = clickedDeleteButton.closest('.project-card');
            const projectId = projectCard.dataset.projectId;

            // Confirmation dialog
            if (confirm("Are you sure you want to delete this project? This action cannot be undone.")) {
                deleteProject(projectId, projectCard);
            }
        }

        // Toggle options menu
        if (clickedOptionButton) {
            const optionContainer = clickedOptionButton.nextElementSibling;

            document.querySelectorAll('.option-container').forEach((container) => {
                if (container !== optionContainer) {
                    container.style.display = 'none';
                }
            });

            optionContainer.style.display =
                optionContainer.style.display === 'flex' ? 'none' : 'flex';
        } else if (!clickedOptionContainer) {
            document.querySelectorAll('.option-container').forEach((container) => {
                container.style.display = 'none';
            });
        }
    });
    

    const addProjectBtnHome = document.getElementById('addProjectBtn');

    addProjectBtnHome.addEventListener('click', function(){
        addProjectModal.classList.add('show');
    });

    function fetchProjects() {
        const projectsList = document.querySelector('.projects-list');
        const noProj = document.querySelector('.noProj');
    
        fetch('../controller/fetch_projects.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const projects = data.projects;
    
                    if (projects.length > 0) {
                        projects.forEach(project => {
                            const projectCard = document.createElement('div');
                            projectCard.classList.add('project-card');
                            
                            projectCard.dataset.projectId = project.project_id;

                            // Click event to check role and redirect
                            projectCard.addEventListener('click', function (event) {
                                if (event.target.closest('.option-btn') || event.target.closest('.option-container')) {
                                    return;
                                }

                                const projectId = projectCard.dataset.projectId;
                                checkUserRole(projectId);
                            });


                            let membersHTML = '';
                            const members = project.members || [];
    
                            // limited lang sa 3 yung member icon
                            if (members.length > 0) {
                                members.slice(0, 3).forEach(member => {
                                    membersHTML += `
                                        <img src="${member.profile_picture}" alt="${member.username}" class="member-icon">
                                    `;
                                });
    
                                // kapag above 3 yung members maglalagay lang bagong icon tapos bilang ng remaining members
                                if (members.length > 3) {
                                    const additionalMembersCount = members.length - 3;
                                    membersHTML += `
                                        <div class="member-icon more-members">
                                            +${additionalMembersCount}
                                        </div>
                                    `;
                                }
                            } else {
                                membersHTML = '<p>No members added yet</p>';
                            }
    
                            projectCard.innerHTML = `
                                <div class="card-header">
                                    <h5 class="project-title">${project.project_name}</h5>
                                    <button class="option-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <div class="option-container">
                                        <button ><i class="fa-regular fa-box-archive"></i> Archive</button>
                                        <button id="deleteProject"><i class="fa-light fa-trash"></i> Delete</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <span class="status">${project.status}</span>
                                    <div class="dueDate">
                                        <h5>Due Date:</h5>
                                        <p>${project.due_date}</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="card-members">
                                        <p>Members</p>
                                        <div class="member-icons">
                                            ${membersHTML}
                                        </div>
                                    </div>
                                </div>
                            `;
    
                            projectsList.insertBefore(projectCard, projectsList.querySelector('.add-project'));
                        });
                    } else {
                        noProj.style.display = 'block';
                    }
                } else {
                    console.error('Failed to fetch projects:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Function to check the user's role and redirect to appropriate shit
function checkUserRole(projectId) {
    fetch(`../controller/check_user_role.php?project_id=${projectId}`, {
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
                    window.location.href = `Project-Manager/Home.php?project_id=${projectId}`;
                } else {
                    // kapag member
                    window.location.href = `Member/Home.php?project_id=${projectId}`;
                }
            } else {
                console.error('Failed to check user role:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
function deleteProject(projectId, projectCard) {
    fetch('../controller/delete_project.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ project_id: projectId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                projectCard.remove(); // Remove the project card from the UI
            } else {
                alert(`Failed to delete project: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error deleting project:', error);
        });
}   
    

    fetchProjects();

});

