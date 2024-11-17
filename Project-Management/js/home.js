document.addEventListener('DOMContentLoaded', function () {
    const projectsList = document.querySelector('.projects-list');

    projectsList.addEventListener('mouseover', function (event) {
        const projectCard = event.target.closest('.project-card');
        if (projectCard) {
            const optionButton = projectCard.querySelector('.option-btn');
            if (optionButton) {
                optionButton.style.display = 'block';
            }
        }
    });

    projectsList.addEventListener('mouseout', function (event) {
        const projectCard = event.target.closest('.project-card');
        if (projectCard) {
            const optionButton = projectCard.querySelector('.option-btn');
            const optionContainer = projectCard.querySelector('.option-container');

            if (optionButton && optionContainer.style.display !== 'flex') {
                optionButton.style.display = 'none';
            }
        }
    });

    projectsList.addEventListener('click', function (event) {
        const clickedOptionButton = event.target.closest('.option-btn');
        const clickedOptionContainer = event.target.closest('.option-container');

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

    function fetchProjects(){
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
                    
                    if(projects.length > 0){
                        projects.forEach(project => {
                            const projectCard = document.createElement('div');
                            projectCard.classList.add('project-card');
                            projectCard.innerHTML = `
                                <button class="option-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div class="option-container">
                                    <button><i class="fa-regular fa-box-archive"></i> Archive</button>
                                    <button><i class="fa-light fa-trash"></i> Delete</button>
                                </div>
                                <h5 class="project-title">${project.project_name}</h5>
                            `;
        
                            projectsList.insertBefore(projectCard, projectsList.querySelector('.add-project'));
                        });
                    }else{
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

    fetchProjects();

});