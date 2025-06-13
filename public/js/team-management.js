// Team Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get status data from the data attribute
    const statusDataElement = document.getElementById('status-data');
    if (!statusDataElement) return;

    try {
        const statuses = JSON.parse(statusDataElement.dataset.statuses || '[]');
        
        // Apply status colors to badges
        statuses.forEach(function(status) {
            document.querySelectorAll('.status-badge-' + status.id).forEach(function(el) {
                el.style.backgroundColor = status.color;
            });
        });
    } catch (error) {
        console.error('Error initializing status badges:', error);
    }
});
