// Function to handle the change event of the adminStatus select element
function handleStatusChange() {
    const statusSelect = document.getElementById("adminstatus");
    const unavailabilityContainer = document.getElementById("unavailabilityContainer");

    if (statusSelect.value === "set away") {
        unavailabilityContainer.style.display = "block";
    } else {
        unavailabilityContainer.style.display = "none";
    }
}

// Add event listener to the adminStatus select element
document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById("adminstatus");
    statusSelect.addEventListener("change", handleStatusChange);
});
