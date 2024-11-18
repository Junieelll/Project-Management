function openTab(event, tabName) {
    var tabContents = document.querySelectorAll(".tab-content");
    tabContents.forEach(content => content.classList.remove("active"));
  
    var tabButtons = document.querySelectorAll(".tab-button");
    tabButtons.forEach(button => button.classList.remove("active"));
  
    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
  }

  document.querySelector('.dropdown-btn.projects-btn').addEventListener('click', function () {
    this.parentElement.classList.toggle('active');
});

document.querySelector('.dropdown-btn.all-task').addEventListener('click', function () {
    this.parentElement.classList.toggle('active');
});
  