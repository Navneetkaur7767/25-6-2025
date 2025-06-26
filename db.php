<?php

session_start();

$servername = "localhost";
$username = "localhost";
$password = "NAVneet345@";
$dbname = "myForm";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create events table if not exists
$createTableSql = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_title VARCHAR(255) NOT NULL,
    startdate DATETIME NOT NULL,
    enddate DATETIME DEFAULT NULL,
    adddate DATETIME DEFAULT NULL,
    editdate DATETIME DEFAULT NULL,
    user_email VARCHAR(100) NOT NULL
)";

$conn->query($createTableSql);

// Get current user's email safely
$userEmail = $_SESSION['email'] ?? '';
$safeEmail = $conn->real_escape_string($userEmail);

?>

if (!response.success) return alert("Move failed: " + response.message);

// Remove old strip(s)
document.querySelectorAll(`#event-${data.eventId}`).forEach(e => e.remove());

const newStartDate = new Date(droppedDate);
const newEndDate = new Date(newStartDate);
newEndDate.setDate(newStartDate.getDate() + data.duration - 1);

// Loop through all weeks
document.querySelectorAll('.calendar-row').forEach(row => {
  const overlay = row.querySelector('.event-overlay-container');
  const weekStartCell = row.querySelector('td[data-date]');
  if (!weekStartCell) return;

  const weekStart = new Date(weekStartCell.dataset.date);
  const weekEnd = new Date(weekStart);
  weekEnd.setDate(weekEnd.getDate() + 6);

  if (newEndDate < weekStart || newStartDate > weekEnd) return;

  const actualStart = newStartDate < weekStart ? weekStart : newStartDate;
  const actualEnd = newEndDate > weekEnd ? weekEnd : newEndDate;

  const offsetDays = (normalize(actualStart) - normalize(weekStart)) / 86400000;
  const duration = (normalize(actualEnd) - normalize(actualStart)) / 86400000 + 1;

  const left = offsetDays * 185.5;
  const width = duration * 185.5;

  // lane stacking
  const existingStrips = overlay.querySelectorAll('.event-strip');
  let laneIndex = 0;
  while (true) {
    let conflict = false;
    for (let strip of existingStrips) {
      let stripLeft = parseFloat(strip.style.left);
      let stripWidth = parseFloat(strip.style.width);
      let stripRight = stripLeft + stripWidth;
      let newLeft = left;
      let newRight = left + width;
      let stripTop = parseFloat(strip.style.top);
      if (stripTop !== laneIndex * 28) continue;
      if (!(newRight <= stripLeft || newLeft >= stripRight)) {
        conflict = true;
        break;
      }
    }
    if (!conflict) break;
    laneIndex++;
  }

  const topOffset = laneIndex * 28;
  const oldTitle = document.getElementById(`title-${data.eventId}`)?.textContent || 'Moved Event';

  const eventDiv = document.createElement('div');
  eventDiv.className = 'event-strip';
  eventDiv.id = `event-${data.eventId}`;
  eventDiv.setAttribute('draggable', 'true');
  eventDiv.style.cssText = `position:absolute;top:${topOffset}px;left:${left}px;width:${width}px;`;
  eventDiv.title = oldTitle;
  eventDiv.innerHTML = `
    <span class="event-text" id="title-${data.eventId}">${oldTitle}</span>
    <span class="event-actions">
      <button class="edit-btn" onclick="event.stopPropagation(); promptEditEvent(${data.eventId})"><i class="fa fa-pencil"></i></button>
      <button class="dlt-btn" onclick="event.stopPropagation(); deleteEvent(${data.eventId})"><i class="fa fa-remove"></i></button>
    </span>`;

  overlay.appendChild(eventDiv);
  bindDragEvents(eventDiv, data.duration);
});

replace this 
const row = cell.closest('.calendar-row');
const overlay = row.querySelector('.event-overlay-container');
const weekStart = new Date(row.querySelector('td[data-date]').dataset.date);
// ...
overlay.appendChild(eventDiv);
bindDragEvents(eventDiv, data.duration);
          


// ✅ 1. Remove all old strips of this event ID (in case it spanned multiple rows earlier)
document.querySelectorAll(`#event-${data.eventId}`).forEach(e => e.remove());

// ✅ 2. Recalculate new start and end date based on drop
const newStartDate = new Date(droppedDate);
const newEndDate = new Date(newStartDate);
newEndDate.setDate(newStartDate.getDate() + data.duration - 1);

// ✅ 3. Loop through all rows to render partial strip in each overlapping row
document.querySelectorAll('.calendar-row').forEach(row => {
  const overlay = row.querySelector('.event-overlay-container');
  const weekStartCell = row.querySelector('td[data-date]');
  if (!weekStartCell) return;

  const weekStart = new Date(weekStartCell.dataset.date);
  const weekEnd = new Date(weekStart);
  weekEnd.setDate(weekEnd.getDate() + 6);

  // ✅ 4. Skip row if no overlap
  if (newEndDate < weekStart || newStartDate > weekEnd) return;

  // ✅ 5. Trim strip to only part of event that overlaps this row
  const actualStart = newStartDate < weekStart ? weekStart : newStartDate;
  const actualEnd = newEndDate > weekEnd ? weekEnd : newEndDate;

  const offsetDays = (normalize(actualStart) - normalize(weekStart)) / 86400000;
  const duration = (normalize(actualEnd) - normalize(actualStart)) / 86400000 + 1;

  const left = offsetDays * 185.5;
  const width = duration * 185.5;

  // ✅ 6. Lane stacking logic (same as before)
  const existingStrips = overlay.querySelectorAll('.event-strip');
  let laneIndex = 0;
  while (true) {
    let conflict = false;
    for (let strip of existingStrips) {
      let stripLeft = parseFloat(strip.style.left);
      let stripWidth = parseFloat(strip.style.width);
      let stripRight = stripLeft + stripWidth;
      let newLeft = left;
      let newRight = left + width;
      let stripTop = parseFloat(strip.style.top);
      if (stripTop !== laneIndex * 28) continue;
      if (!(newRight <= stripLeft || newLeft >= stripRight)) {
        conflict = true;
        break;
      }
    }
    if (!conflict) break;
    laneIndex++;
  }

  const topOffset = laneIndex * 28;
  const oldTitle = document.getElementById(`title-${data.eventId}`)?.textContent || 'Moved Event';

  // ✅ 7. Create new strip for this row segment
  const eventDiv = document.createElement('div');
  eventDiv.className = 'event-strip';
  eventDiv.id = `event-${data.eventId}`;
  eventDiv.setAttribute('draggable', 'true');
  eventDiv.style.cssText = `position:absolute;top:${topOffset}px;left:${left}px;width:${width}px;`;
  eventDiv.title = oldTitle;
  eventDiv.innerHTML = `
    <span class="event-text" id="title-${data.eventId}">${oldTitle}</span>
    <span class="event-actions">
      <button class="edit-btn" onclick="event.stopPropagation(); promptEditEvent(${data.eventId})"><i class="fa fa-pencil"></i></button>
      <button class="dlt-btn" onclick="event.stopPropagation(); deleteEvent(${data.eventId})"><i class="fa fa-remove"></i></button>
    </span>`;

  overlay.appendChild(eventDiv);
  bindDragEvents(eventDiv, data.duration);
});


.then(response => {
  if (!response.success) return alert("Move failed: " + response.message);

  // Remove all old strips (maybe spanned multiple rows)
  document.querySelectorAll(`#event-${data.eventId}`).forEach(e => e.remove());

  const newStartDate = new Date(droppedDate);
  const newEndDate = new Date(newStartDate);
  newEndDate.setDate(newStartDate.getDate() + data.duration - 1);

  // Loop through all rows to re-render the full span
  document.querySelectorAll('.calendar-row').forEach(row => {
    const overlay = row.querySelector('.event-overlay-container');
    const weekStartCell = row.querySelector('td[data-date]');
    if (!weekStartCell) return;

    const weekStart = new Date(weekStartCell.dataset.date);
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);

    if (newEndDate < weekStart || newStartDate > weekEnd) return;

    const actualStart = newStartDate < weekStart ? weekStart : newStartDate;
    const actualEnd = newEndDate > weekEnd ? weekEnd : newEndDate;

    const offsetDays = (normalize(actualStart) - normalize(weekStart)) / 86400000;
    const duration = (normalize(actualEnd) - normalize(actualStart)) / 86400000 + 1;

    const left = offsetDays * 185.5;
    const width = duration * 185.5;

    // Lane stacking
    const existingStrips = overlay.querySelectorAll('.event-strip');
    let laneIndex = 0;
    while (true) {
      let conflict = false;
      for (let strip of existingStrips) {
        let stripLeft = parseFloat(strip.style.left);
        let stripWidth = parseFloat(strip.style.width);
        let stripRight = stripLeft + stripWidth;
        let newLeft = left;
        let newRight = left + width;
        let stripTop = parseFloat(strip.style.top);
        if (stripTop !== laneIndex * 28) continue;
        if (!(newRight <= stripLeft || newLeft >= stripRight)) {
          conflict = true;
          break;
        }
      }
      if (!conflict) break;
      laneIndex++;
    }

    const topOffset = laneIndex * 28;
    const oldTitle = document.getElementById(`title-${data.eventId}`)?.textContent || 'Moved Event';

    const eventDiv = document.createElement('div');
    eventDiv.className = 'event-strip';
    eventDiv.id = `event-${data.eventId}`;
    eventDiv.setAttribute('draggable', 'true');
    eventDiv.style.cssText = `position:absolute;top:${topOffset}px;left:${left}px;width:${width}px;`;
    eventDiv.title = oldTitle;
    eventDiv.innerHTML = `
      <span class="event-text" id="title-${data.eventId}">${oldTitle}</span>
      <span class="event-actions">
        <button class="edit-btn" onclick="event.stopPropagation(); promptEditEvent(${data.eventId})"><i class="fa fa-pencil"></i></button>
        <button class="dlt-btn" onclick="event.stopPropagation(); deleteEvent(${data.eventId})"><i class="fa fa-remove"></i></button>
      </span>`;

    overlay.appendChild(eventDiv);
    bindDragEvents(eventDiv, data.duration);
  });
})


cell.addEventListener('drop', e => {
  e.preventDefault();

  const droppedDate = cell.dataset.date;
  const data = JSON.parse(e.dataTransfer.getData('text/plain'));
  const newStart = new Date(droppedDate);
  const newEnd = new Date(newStart);
  newEnd.setDate(newStart.getDate() + data.duration - 1);

  fetch('move_event.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: new URLSearchParams({
      event_id: data.eventId,
      new_start: droppedDate,
      new_end: newEnd.toISOString().slice(0, 10)
    })
  })
  .then(res => res.json())
  .then(response => {
    if (!response.success) return alert("Move failed: " + response.message);

    // Remove all old instances of the event
    document.querySelectorAll(`#event-${data.eventId}`).forEach(e => e.remove());

    const eventStart = new Date(droppedDate);
    const eventEnd = new Date(eventStart);
    eventEnd.setDate(eventStart.getDate() + data.duration - 1);

    document.querySelectorAll('.calendar-row').forEach(row => {
      const overlay = row.querySelector('.event-overlay-container');
      const weekStartCell = row.querySelector('td[data-date]');
      if (!weekStartCell) return;

      const weekStart = new Date(weekStartCell.dataset.date);
      const weekEnd = new Date(weekStart);
      weekEnd.setDate(weekEnd.getDate() + 6);

      // If event doesn't intersect with this row, skip
      if (eventEnd < weekStart || eventStart > weekEnd) return;

      const actualStart = eventStart < weekStart ? weekStart : eventStart;
      const actualEnd = eventEnd > weekEnd ? weekEnd : eventEnd;

      const offset = (normalize(actualStart) - normalize(weekStart)) / 86400000;
      const spanDays = (normalize(actualEnd) - normalize(actualStart)) / 86400000 + 1;

      const left = offset * 185.5;
      const width = spanDays * 185.5;

      // Handle overlapping lanes
      const existing = overlay.querySelectorAll('.event-strip');
      let lane = 0;
      while (true) {
        let conflict = false;
        for (let strip of existing) {
          if (parseFloat(strip.style.top) !== lane * 28) continue;
          const l = parseFloat(strip.style.left);
          const r = l + parseFloat(strip.style.width);
          if (!(left + width <= l || left >= r)) {
            conflict = true;
            break;
          }
        }
        if (!conflict) break;
        lane++;
      }

      const top = lane * 28;
      const titleEl = document.getElementById(`title-${data.eventId}`);
      const titleText = titleEl ? titleEl.textContent : "Moved Event";

      const newStrip = document.createElement('div');
      newStrip.className = 'event-strip';
      newStrip.id = `event-${data.eventId}`;
      newStrip.setAttribute('draggable', 'true');
      newStrip.title = titleText;
      newStrip.style.cssText = `position:absolute;top:${top}px;left:${left}px;width:${width}px;`;

      newStrip.innerHTML = `
        <span class="event-text" id="title-${data.eventId}">${titleText}</span>
        <span class="event-actions">
          <button class="edit-btn" onclick="event.stopPropagation(); promptEditEvent(${data.eventId})"><i class="fa fa-pencil"></i></button>
          <button class="dlt-btn" onclick="event.stopPropagation(); deleteEvent(${data.eventId})"><i class="fa fa-remove"></i></button>
        </span>
      `;

      overlay.appendChild(newStrip);
      bindDragEvents(newStrip, data.duration);
    });
  })
  .catch(err => {
    alert("Drop failed");
    console.error(err);
  });
});
