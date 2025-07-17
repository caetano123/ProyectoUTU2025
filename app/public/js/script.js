
function toggleDropdown() {
  const content = document.getElementById("dropdownContent");
  content.style.display = (content.style.display === "block") ? "none" : "block";
}

document.addEventListener('click', function(event) {
  const dropdown = document.querySelector('.dropdown');
  const content = document.getElementById("dropdownContent");
  if (!dropdown.contains(event.target)) {
    content.style.display = 'none';
  }
});

