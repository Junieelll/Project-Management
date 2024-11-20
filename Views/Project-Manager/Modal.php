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
    <script src="../../js/Project-Manager-JS/drag.js" defer></script>
</head>
<body>
 <!-- View Task -->
<div class="task-overlay-admin" id="taskModalOverlayAdmin"></div>
<div class="task-modal-admin" id="taskModalAdmin">
    <div class="task-modal-content-admin">
        <div class="close-btn-admin" id="closeTaskModalAdmin">
            <img src="IMG/close.png" alt="Close" onclick="">
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

<!-- Add Task -->
<div class="modal-add" id="newTaskModalAdd">

    <button  class="btn-task-submit" onclick="closeaddtaskModal()"> Close </button>
    <div class="form-group-add">
        <label for="task-title-add" class="form-group-addtext2">Task Title:</label>
        <input type="text" id="task-title-add" name="task-title-add">
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
    </div>

    <div class="form-group-add">
        <label for="due-date-add" class="form-group-addtext3">Due date:</label>
        <input type="date" id="due-date-add" name="due-date-add">
    </div>

    <div class="form-group-add">
        <label for="priority-add" class="form-group-addtext4">Priority:</label>
        <select id="priority-add" name="priority-add">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>
    </div>

    <div class="form-group-add">
        <label for="group-add" class="form-group-addtext6">Priority:</label>
        <select id="group-add" name="group-add">
            <option value="Back-End">Back-End</option>
            <option value="Front-End">Front-End</option>
            <option value="Documents">Documents</option>
            <option value="Documents">High</option>
        </select>
    </div>

    <div class="form-group-add"> 
        <label for="note-add" class="form-group-addtext5">Note:</label>
        <textarea id="note-add" name="note-add"></textarea>
    </div>

    <button type="submit" class="btn-task-submit" id="createTaskBtnAdd" onclick=""> Submit </button>
</div>

</body>
</html>




