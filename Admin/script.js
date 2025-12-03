const img = document.getElementById('hotspot-image');
const coordsDisplay = document.getElementById('coords');
const inputX = document.getElementById('punt_positie_x');
const inputY = document.getElementById('punt_positie_y');

img.addEventListener('mousemove', function(e) {
    const rect = img.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const percentX = Math.round((x / rect.width) * 100);
    const percentY = Math.round((y / rect.height) * 100);
    coordsDisplay.textContent = `X: ${percentX}%, Y: ${percentY}%`;
});

img.addEventListener('click', function(e) {
    const rect = img.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const percentX = Math.round((x / rect.width) * 100);
    const percentY = Math.round((y / rect.height) * 100);
    inputX.value = percentX;
    inputY.value = percentY;
});
