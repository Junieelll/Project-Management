document.addEventListener("DOMContentLoaded", async () => {
    // Get the project_id from the URL


    // const urlParams = new URLSearchParams(window.location.search);
    // const projectId = urlParams.get("project_id");

    if (!projectId) {
        console.error("Project ID is missing in the URL.");
        return;
    }

    // Fetch tasks using fetch API
    try {
        const response = await fetch(`../../controller/fetch_task.php?project_id=${projectId}`);
        const data = await response.json();

        if (!data.success) {
            console.error("Failed to fetch tasks:", data.message);
            return;
        }

        const tasks = data.tasks;
        const tasksContainer = document.querySelector(".tasks ul");

        // Clear existing tasks
        tasksContainer.innerHTML = "";

        // Populate tasks dynamically
        tasks.forEach(task => {
            const taskElement = document.createElement("li");

            // Task content
            taskElement.innerHTML = `
                <span>${task.task_title}</span>
                <span>Due: ${new Date(task.due_date).toLocaleDateString()}</span>
                <span>Status: ${task.status}</span>
            `;

            // Apply color based on task priority
            switch (task.priority) {
                case "High":
                    taskElement.style.color = "var(--red)";
                    break;
                case "Medium":
                    taskElement.style.color = "var(--yellow)";
                    break;
                case "Low":
                    taskElement.style.color = "var(--green)";
                    break;
                default:
                    taskElement.style.color = "#333";
            }

            tasksContainer.appendChild(taskElement);
        });
        updateStats(tasks);
        // Fetch project members
         // Fetch both project members and manager in parallel
         const [membersResponse, managerResponse] = await Promise.all([
            fetch(`../../controller/get_projectmembers.php?project_id=${projectId}`),
            fetch(`../../controller/get_projectmanager.php?project_id=${projectId}`)
        ]);

        // Parse both responses
        const membersData = await membersResponse.json();
        const managerData = await managerResponse.json();

        // Check for success in both responses
        if (membersData.success && managerData.success) {
            updateProjectData(membersData.tasks, managerData.tasks); // Update both members and manager
        } else {
            console.error("Failed to fetch project data.");
        }
    } catch (error) {
        console.error("An error occurred while fetching tasks:", error);
    }
});
function updateStats(tasks) {
    const totalTasks = tasks.length;
    const inProgressTasks = tasks.filter(task => task.status !== "Finished").length;
    const finishedTasks = tasks.filter(task => task.status === "Finished").length;

    document.querySelector(".stat-item:nth-child(1) p").textContent = totalTasks;
    document.querySelector(".stat-item:nth-child(2) p").textContent = inProgressTasks;
    document.querySelector(".stat-item:nth-child(3) p").textContent = finishedTasks;
}
function updateProjectData(members, manager) {
    const usersContainer = document.querySelector(".users-container");

    let userCount = 0;
    // Clear existing user cards
    usersContainer.innerHTML = "";
    if (manager && manager.length > 0) {
        const managerCard = document.createElement("div");
        managerCard.classList.add("user-card", "owner");

        managerCard.innerHTML = `
            <div class="badge">Owner</div>
            <div class="avatar">
                <img src="${manager[0].assigned_user_picture || 'https://via.placeholder.com/50'}" alt="Manager">
            </div>
            <h3>${manager[0].assigned_user_name}</h3>
            <p>${manager[0].assigned_user_email || 'No Email Available'}</p>
        `;

        usersContainer.appendChild(managerCard);

        userCount++;
    }

    // Loop through the members and generate user cards
    members.forEach(members => {
        const userCard = document.createElement("div");
        userCard.classList.add("user-card");

        userCard.innerHTML = `
            <div class="badge">Member</div>
            <div class="avatar">
                <img src="${members.assigned_user_picture || 'https://via.placeholder.com/50'}" alt="User">
            </div>
            <h3>${members.assigned_user_name}</h3>
            <p>${members.assigned_user_email || 'No Email Available'}</p>
            <div class="user-stats">
                <span>${members.task_count || 0} Tasks</span>
            </div>
        `;

        usersContainer.appendChild(userCard);

        userCount++;
    });

    const userCountElement = document.querySelector(".stat-item:nth-child(4) p");
    if (userCountElement) {
        userCountElement.textContent = `${userCount}`;
    }

    // Create manager card (only if manager exists in response)
    
}